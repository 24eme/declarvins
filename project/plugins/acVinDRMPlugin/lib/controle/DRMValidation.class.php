<?php
class DRMValidation
{
	private $drm;
	private $drmSuivante;
	private $drmPrecedente;
	private $engagements;
	private $warnings;
	private $errors;
	private $isAdmin;
	private $isCiel;
	private $etablissement;
	const VINSSANSIG_KEY = 'VINSSANSIG';
	const VCI_KEY = 'VCI';
	const AOP_KEY = 'AOP';
	const IGP_KEY = 'IGP';
	const NO_LINK = '#';
	const ECART_VRAC = 0.2;

	public function __construct($drm, $options = null)
	{
		$this->drm = $drm;
		$this->drmSuivante = $drm->getSuivante();
		$this->drmPrecedente = $drm->getPrecedente();
		$this->options = $options;
		$this->engagements = array();
		$this->warnings = array();
		$this->errors = array();
		$this->etablissement = $drm->getEtablissementObject();
		$this->isAdmin = ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER);
		$this->isCiel = ($this->etablissement->isTransmissionCiel() || $drm->isNegoce());
		if ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_EDI) {
			$this->isCiel = false;
		}
		$this->controleDRM();
	}

	public function getEngagements()
	{
		return $this->engagements;
	}

	public function getWarnings()
	{
		return $this->warnings;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	private function controleDRM()
	{
		$totalEntreeDeclassement = 0;
		$totalSortiDeclassement = 0;
		$totalEntreeVci = 0;
		$totalSortiVci = 0;
		$totalVciEntree = 0;
		$totalVciSorti = 0;
		$certificationVinssansig = null;
		$certificationVci = null;
		$certificationFirst = null;
		foreach ($this->drm->declaration->certifications as $certification) {
			if ($certification->getKey() == self::VINSSANSIG_KEY) {
				$certificationVinssansig = $certification;
			}
			if (!$certificationFirst) {
				$certificationFirst = $certification;
			}
			$totalEntreeRepli = 0;
			$totalSortiRepli = 0;
			$details = $certification->getProduits();
			foreach ($details as $detail) {
				$this->controleEngagements($detail);
				$this->controleErrors($detail);
				$this->controleWarnings($detail);
				if (!$detail->isVci()) {
					$totalEntreeRepli += $detail->entrees->repli;
					$totalSortiRepli += $detail->sorties->repli;
				}
				$totalSortiDeclassement += $detail->sorties->declassement;
				if (!$this->drm->isNegoce() && $certification->getKey() == self::AOP_KEY && $detail->sorties->repli) {
					$this->engagements['odg'] = new DRMControleEngagement('odg');
				}
				if (!$this->drm->isNegoce() && $certification->getKey() == self::IGP_KEY && $detail->entrees->declassement) {
					$this->engagements['odg'] = new DRMControleEngagement('odg');
				}
				if ($certification->getKey() == self::VINSSANSIG_KEY) {
					$totalEntreeDeclassement += $detail->entrees->declassement;
				}
				if ($detail->isVci()) {
					$certificationVci = $certification;
					$totalVciEntree += $detail->entrees->recolte;
					$totalVciSorti += $detail->sorties->repli;
				} else {
					$certificationVci = $certification;
					$totalEntreeVci += $detail->entrees->vci;
					$totalSortiVci += $detail->sorties->vci;
				}
			}
			if (round(abs($totalEntreeRepli - $totalSortiRepli),5) > 0.0001 && !$this->isAdmin) {
				$this->errors['repli_'.$certification->getKey()] = new DRMControleError('repli', $this->generateUrl('drm_recap', $certification), $certification->getLibelleEtape().': %message% (entrée: '.$totalEntreeRepli.' / sortie: '.$totalSortiRepli.')');
			}
		}
		if (round($totalEntreeDeclassement,5) > round($totalSortiDeclassement,5) && !$this->isAdmin) {
			$this->errors['declassement_'.self::VINSSANSIG_KEY] = new DRMControleError('declassement', $this->generateUrl('drm_recap', $certificationVinssansig));
		}
		if (round($totalVciEntree,5) != round($totalSortiVci,5) || round($totalVciSorti,5) != round($totalEntreeVci,5)) {
			if ($certificationVci) {
				//$this->errors['vci_'.self::VCI_KEY] = new DRMControleError('vci', $this->generateUrl('drm_recap', $certificationVci));
			} else {
				//$this->errors['vci_'.self::VCI_KEY] = new DRMControleError('vci', $this->generateUrl('drm_recap', $certificationFirst));
			}
		}
		$drmCiel = $this->drm->getOrAdd('ciel');
		if ($this->drm->isRectificative() && $drmCiel->isTransfere() && !$drmCiel->isValide() && $drmCiel->diff) {
			$xmlIn = simplexml_load_string($drmCiel->diff, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
			$export = new DRMExportCsvEdi($this->drm);
			if ($xml = $export->exportEDI('xml')) {
				$xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
				$compare = new DRMCielCompare($xmlIn, $xmlOut);
				if ($compare->hasDiff()) {
					$this->errors['diff_ciel'] = new DRMControleError('diff_ciel', $this->generateUrl('drm_validation', $this->drm));
				}
			} else {
				$this->errors['diff_ciel'] = new DRMControleError('diff_ciel', $this->generateUrl('drm_validation', $this->drm));
			}
		}
		if ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_DTI && !$this->isCiel) {
			if (!$this->drm->get('declaratif')->get('paiement')->get('douane')->get('frequence')) {
				$this->errors['droits_frequence'] = new DRMControleError('droits_frequence', $this->generateUrl('drm_declaratif', $this->drm));
			}
		}

        foreach ($this->drm->getProduitsReserveInterpro() as $produit) {
            if ($produit->getVolumeCommercialisable() < 0) {
								if ($produit->getInterpro() == 'INTERPRO-CIVP') {
									$this->errors['reserve_interpro_'.$produit->getIdentifiantHTML()] = new DRMControleError('reserve_interpro', $this->generateUrl('drm_recap', $produit->getLieu()), $produit->makeFormattedLibelle().': %message%');
								} else {
									$this->warnings['reserve_interpro_'.$produit->getIdentifiantHTML()] = new DRMControleWarning('reserve_interpro', $this->generateUrl('drm_recap', $produit->getLieu()), $produit->makeFormattedLibelle().': %message%');
								}
            } elseif ($produit->getReserveInterpro() > 0 && ($produit->total / $produit->getReserveInterpro()) < 1.2) {
                $this->warnings['reserve_interpro_'.$produit->getIdentifiantHTML()] = new DRMControleWarning('reserve_interpro', $this->generateUrl('drm_recap', $produit->getLieu()), $produit->makeFormattedLibelle().': %message%');
            }
        }

				foreach ($this->drm->crds as $crdId => $crd) {
					if (($crd->entrees->autres > 0 || $crd->sorties->autres > 0) && !$crd->observations) {
							$this->errors['obs_crd_autres_'.$crdId] = new DRMControleError('obs_crd_autres', $this->generateUrl('drm_declaratif', $this->drm), 'CRD '.$crd->libelle.': %message%');
					}
				}
	}

	public function isValide() {
	  return !($this->hasErrors());
	}

	private function controleEngagements($detail)
	{
	    if ($this->drm->isNegoce()) {
	        return;
	    }
		if ($detail->sorties->export > 0) {
			$this->engagements['export'] = new DRMControleEngagement('export');
		}
		if ($detail->sorties->declassement > 0) {
			$this->engagements['declassement'] = new DRMControleEngagement('declassement');
		}
		if ($detail->sorties->repli > 0) {
			$this->engagements['repli'] = new DRMControleEngagement('repli');
		}
		if ($detail->sorties->pertes > 0) {
			$this->engagements['pertes'] = new DRMControleEngagement('pertes');
		}
	}

	private function controleErrors($detail)
	{
		$totalVolume = 0;
		if ($detail->total < 0) {
			$this->errors['total_negatif_'.$detail->getIdentifiantHTML()] = new DRMControleError('total_negatif', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
		if ($detail->acq_total < 0) {
			$this->errors['total_negatif_'.$detail->getIdentifiantHTML()] = new DRMControleError('total_negatif', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
		if (round($detail->total,5) < round($detail->stocks_fin->instance,5)) {
			$this->errors['total_stocks_'.$detail->getIdentifiantHTML()] = new DRMControleError('total_stocks', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
		if (isset($this->options['is_operateur']) && !$this->options['is_operateur']) {
			if (!isset($this->options['no_vrac']) || ! $this->options['no_vrac']) {
			  foreach ($detail->vrac as $contrat) {
			    $totalVolume += $contrat->volume;
			  }
			  if (($detail->canHaveVrac() && $detail->getTotalVrac()) || count($detail->vrac->toArray()) > 0) {
			  	  $ecart = round($detail->getTotalVrac() * self::ECART_VRAC,5);
				  if (round($totalVolume,5) < (round($detail->getTotalVrac(),5) - $ecart) || round($totalVolume,5) > (round($detail->getTotalVrac(),5) + $ecart)) {
				  	if ($detail->getCertification()->getKey() == self::IGP_KEY || $detail->interpro == 'INTERPRO-CIVP' || $this->etablissement->famille != 'producteur') {
				  		$this->warnings['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': %message%');
				    } else {
				    	$this->errors['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleError('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': %message%');
				    }
				  }
			  }
			}
		}
		if ($drmSuivante = $this->drmSuivante) {
			if ($drmSuivante->exist($detail->getHash())) {
				$d = $drmSuivante->get($detail->getHash());
				if (round($d->total_debut_mois,5) != round($detail->total,5) && !$this->drm->hasVersion()) {
					if(isset($this->options['stock']) && $this->options['stock'] == 'warning') {
						$this->warnings['stock_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('stock', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					} else {
						$this->errors['stock_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					}
				}
				if (round($d->acq_total_debut_mois,5) != round($detail->acq_total,5) && !$this->drm->hasVersion()) {
					if(isset($this->options['stock']) && $this->options['stock'] == 'warning') {
						$this->warnings['stock_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('stock', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					} else {
						$this->errors['stock_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					}
				}
			}
		}
		if ($drmPrecedente = $this->drmPrecedente) {
			if ($drmPrecedente->exist($detail->getHash()) && !$this->drm->isDebutCampagne()) {
				if (!$this->drm->hasVersion() && !$this->isAdmin) {
					$d = $drmPrecedente->get($detail->getHash());
					if (!$this->drm->canSetStockDebutMois() && round($d->total,5) != round($detail->total_debut_mois,5)) {
						$this->errors['stock_deb_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock_deb', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					}
					if (!$this->drm->canSetStockDebutMois(true) && round($d->acq_total,5) != round($detail->acq_total_debut_mois,5)) {
						$this->errors['stock_deb_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock_deb_acq', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					}
				}
			}
		}
		$crdNeg = false;
		foreach ($this->drm->crds as $crd) {
			if ($crd->total_fin_mois < 0) {
				$crdNeg = true;
			}
		}
		if ($crdNeg) {
			$this->errors['stock_crd'] = new DRMControleError('crd', $this->generateUrl('drm_crd', $this->drm));
		}
		if ($this->isCiel) {
			if ($detail->entrees->crd > 0 && !$detail->observations) {
				$this->errors['observations_crd_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_crd', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			}
			if ($detail->entrees->excedent > 0 && !$detail->observations) {
				$this->errors['observations_excedent_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_excedent', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			}
			if ($detail->sorties->autres > 0 && !$detail->observations) {
				$this->errors['observations_autres_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_autres', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			}
			if ($detail->sorties->pertes > 0 && !$detail->observations) {
				$this->errors['observations_pertes_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_pertes', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			}
			if ($this->drm->isNegoce()) {
			    if ($detail->entrees->declassement > 0 && !$detail->observations) {
			        $this->errors['observations_entrees_declassement_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_entrees_declassement', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->entrees->repli > 0 && !$detail->observations) {
			        $this->errors['observations_entrees_repli_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_entrees_repli', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->entrees->mouvement > 0 && !$detail->observations) {
			        $this->errors['observations_entrees_mouvement_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_entrees_mouvement', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->sorties->autres_interne > 0 && !$detail->observations) {
			        $this->errors['observations_autres_interne_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_autres_interne', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->sorties->declassement > 0 && !$detail->observations) {
			        $this->errors['observations_declassement_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_declassement', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->sorties->repli > 0 && !$detail->observations) {
			        $this->errors['observations_repli_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_repli', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->sorties->mouvement > 0 && !$detail->observations) {
			        $this->errors['observations_mouvement_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_mouvement', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			    if ($detail->sorties->crd_acquittes > 0 && !$detail->observations) {
			        $this->errors['observations_crd_acquittes_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_crd_acquittes', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			    }
			}
			if ($detail->observations && strlen($detail->observations)>250) {
				$this->errors['observations_length_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_length', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			}
		}
		if ($detail->tav && ($detail->tav < 0.5 || $detail->tav > 100)) {
			$this->errors['tav_value_'.$detail->getIdentifiantHTML()] = new DRMControleError('tav_value', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
		foreach ($detail->vrac as $id => $values) {
            $vrac = VracClient::getInstance()->findByNumContrat($id);
            $volume = round($vrac->volume_propose * (1+self::ECART_VRAC), 5);
			if (!$vrac) {
                $this->errors['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleError('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': Il n\'existe pas de contrat numéro '.$id);
            }
            if (isset($values['volume']) && round($values['volume'] + $vrac->volume_enleve,5) > $volume) {
                $this->errors['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleError('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': Le volume de vrac ('.round($values['volume'],5).' hl) est nettement supérieur au volume restant ('.round($vrac->volume_propose - $vrac->volume_enleve,5).' hl) du contrat '.$id);
            }
		}
	}

	private function controleWarnings($detail)
	{
		$totalVolume = 0;
		if (isset($this->options['is_operateur']) && $this->options['is_operateur']) {
			if (!isset($this->options['no_vrac']) || ! $this->options['no_vrac']) {
			  foreach ($detail->vrac as $contrat) {
			    $totalVolume += $contrat->volume;
			  }
			  if (($detail->canHaveVrac() && $detail->getTotalVrac()) || count($detail->vrac->toArray()) > 0) {
			  	  $ecart = round($detail->getTotalVrac() * self::ECART_VRAC,5);
				  if (round($totalVolume,5) < (round($detail->getTotalVrac(),5) - $ecart) || round($totalVolume,5) > (round($detail->getTotalVrac(),5) + $ecart)) {
				    $this->warnings['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': %message%');
				  }
			  }
			}
		}
        $hasTmpMvt = false;
        if ($detail->sorties->mouvement > 0) {
            $this->warnings['mouvement'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#sorties', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
        }
		if ($detail->sorties->embouteillage > 0) {
			$this->warnings['embouteillage_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#sorties', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
		}
		if ($detail->sorties->travail > 0) {
			$this->warnings['travail_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#sorties', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
		}
		if ($detail->sorties->distillation > 0) {
			$this->warnings['distillation_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#sorties', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
		}
        if ($detail->entrees->mouvement > 0) {
            $this->warnings['mouvement'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#entrees', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
        }
        if ($detail->entrees->embouteillage > 0) {
            $this->warnings['embouteillage_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#entrees', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
        }
        if ($detail->entrees->travail > 0) {
            $this->warnings['travail_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#entrees', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
        }
        if ($detail->entrees->distillation > 0) {
            $this->warnings['distillation_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire', $this->generateUrl('drm_recap_detail', $detail).'#entrees', $detail->makeFormattedLibelle().': %message%');
            $hasTmpMvt = true;
        }
        if ($hasTmpMvt && !$detail->observations) {
            $this->warnings['observations_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mvttemporaire_observations', $this->generateUrl('drm_recap_detail', $detail).'#sorties', $detail->makeFormattedLibelle().': %message%');
        }
		/*if (!$detail->hasCvo() || !$detail->hasDouane()) {
			$this->warnings['droits_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('droits', $this->generateUrl('drm_recap_detail', $detail));
		}*/
		if (
			round($detail->total_debut_mois,5) < round($detail->stocks_debut->bloque,5) ||
			round($detail->total_debut_mois,5) < round($detail->stocks_debut->warrante,5) ||
			round($detail->total_debut_mois,5) < round($detail->stocks_debut->instance,5) ||
			round($detail->total_debut_mois,5) < round($detail->stocks_debut->commercialisable,5)
		) {
			$this->warnings['stocksdebut_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('stocksdebut', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
		if ($detail->tav > 0) {
		    $this->warnings['saisiealcool_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('saisiealcool', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
	}

	public function hasEngagements()
	{
		return (count($this->engagements) > 0)? true : false;
	}

	public function hasErrors()
	{
		return (count($this->errors) > 0)? true : false;
	}

	public function hasError($error, $strict = false)
	{
		$keys = array_keys($this->errors);
    	return ($strict)? in_array($error, $keys) : (count(preg_grep('/^'.$error.'_.+$/',$keys)) > 0);
	}

	public function hasWarnings()
	{
		return (count($this->warnings) > 0)? true : false;
	}

	public function find($type, $identifiant)
	{
		if ($type == 'error' && array_key_exists($identifiant, $this->errors)) {

			return $this->errors[$identifiant];
		} elseif($type == 'warning' && array_key_exists($identifiant, $this->warnings)) {

			return $this->warnings[$identifiant];
		} elseif($type == 'engagement' && array_key_exists($identifiant, $this->engagements)) {

			return $this->engagements[$identifiant];
		}

		return null;
	}

	protected function generateUrl($route, $params = array(), $absolute = false)
	{
	  try {
	    return sfContext::getInstance()->getRouting()->generate($route, $params, $absolute);
	  }catch(Exception $e) {
	    return;
	  }
	}

}
