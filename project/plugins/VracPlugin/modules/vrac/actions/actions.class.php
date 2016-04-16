<?php
class vracActions extends acVinVracActions
{
	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $compte, $vrac)
	{
		return VracFormDeclarvinFactory::create($interproId, $etape, $configurationVrac, $etablissement, $compte, $vrac);
	}
	
	protected function saisieTerminee($vrac, $interpro) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
			$saisisseur = $vrac->vous_etes;
			if ($saisisseur && in_array($saisisseur, $acteurs)) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($saisisseur.'_identifiant'));
				if ($etablissement && $compte = $etablissement->getCompteObject()) {
					if ($compte->email) {
						Email::getInstance()->vracSaisieTerminee($vrac, $etablissement, $compte->email);
					}
				}
			}
			unset($acteurs[array_search($saisisseur, $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracDemandeValidationInterpro($vrac, $interpro->email_contrat_vrac, $acteur);
					}
				} else {
					Email::getInstance()->vracDemandeValidation($vrac, $etablissement, $compte->email, $acteur);
				}
			} else {
				if ($email = $interpro->email_contrat_vrac) {
					Email::getInstance()->vracDemandeValidationInterpro($vrac, $email, $acteur);
				}
			}
		}
	}
	
	protected function contratValide($vrac) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$interpros = array();
		$transactionCC = array();
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			if ($compte && $compte->email) {
				$interpro = $this->getInterpro($vrac, $etablissement);
				$interpros[$interpro->_id] = $interpro;
				$configurationVrac = $this->getConfigurationVrac($interpro->_id);
				$pdf = new ExportVracPdf($vrac, $configurationVrac);
    			$pdf->generate();
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracContratValide($vrac, $etablissement, $interpro->email_contrat_vrac);
					}
				} else {
					$transactionCC[$compte->email] = $etablissement->raison_sociale;
					Email::getInstance()->vracContratValide($vrac, $etablissement, $compte->email);
				}
			} else {
				if ($interpro->email_contrat_vrac) {
					Email::getInstance()->vracContratValide($vrac, $etablissement, $interpro->email_contrat_vrac);
				}
			}
		}
		if ($vrac->mode_de_saisie != Vrac::MODE_DE_SAISIE_PAPIER) {
			if ($vrac->exist('oioc') && $vrac->oioc->identifiant) {
				$oioc = OIOCClient::getInstance()->find($vrac->oioc->identifiant);
				$etablissement = EtablissementClient::getInstance()->find($vrac->get('vendeur_identifiant'));
				$configurationVrac = $this->getConfigurationVrac($vrac->interpro);
				$transaction = new ExportVracPdfTransaction($vrac, $configurationVrac, true);
				$transaction->generate();
				Email::getInstance()->vracTransaction($vrac, $etablissement, $oioc, $transactionCC);
			}
		}
	}
	
	protected function contratModifie($vrac) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$interpros = array();
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			if ($compte && $compte->email) {
				$interpro = $this->getInterpro($vrac, $etablissement);
				$interpros[$interpro->_id] = $interpro;
				$configurationVrac = $this->getConfigurationVrac($interpro->_id);
				$pdf = new ExportVracPdf($vrac, $configurationVrac);
    			$pdf->generate();
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
						Email::getInstance()->vracContratModifie($vrac, $etablissement, $interpro->email_contrat_vrac);
					}
				} else {
					Email::getInstance()->vracContratModifie($vrac, $etablissement, $compte->email);
				}
			} else {
				if ($interpro->email_contrat_vrac) {
					Email::getInstance()->vracContratModifie($vrac, $etablissement, $interpro->email_contrat_vrac);
				}
			}
		}
	}
	
	protected function contratValidation($vrac, $acteur) {
		return;
	}
	
	protected function contratAnnulation($vrac, $interpro, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$etab = null;
		if ($vrac->exist('annulation') && $vrac->annulation->etablissement) {
			$etab = EtablissementClient::getInstance()->find($vrac->annulation->etablissement);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro->email_contrat_vrac) {
							Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $interpro->email_contrat_vrac);
					}
				} else {
					Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $compte->email);
				}
			} else {
				if ($interpro && $interpro->email_contrat_vrac) {
					Email::getInstance()->vracContratAnnulation($vrac, $etab, $acteur, $interpro->email_contrat_vrac);
				}
			}
		}	
		if ($vrac->mode_de_saisie != Vrac::MODE_DE_SAISIE_PAPIER) {
			if ($vrac->exist('oioc') && $vrac->oioc->identifiant) {
				$oioc = OIOCClient::getInstance()->find($vrac->oioc->identifiant);
				$etablissement = EtablissementClient::getInstance()->find($vrac->get('vendeur_identifiant'));
				Email::getInstance()->vracTransactionAnnulation($vrac, $etab, $oioc, $oioc->email_transaction);
			}
		}
	}
	

	
	protected function contratDemandeAnnulation($vrac, $interpro, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		$etab = null;
		if ($vrac->annulation->etablissement) {
			$etab = EtablissementClient::getInstance()->find($vrac->annulation->etablissement);
		}
		if ($etab) {
			unset($acteurs[array_search($vrac->getTypeByEtablissement($etab->identifiant), $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro && $interpro->email_contrat_vrac) {
						Email::getInstance()->vracDemandeAnnulationInterpro($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
					}
				} else {
					Email::getInstance()->vracDemandeAnnulation($vrac, $etab, $etablissement, $compte->email, $acteur);
				}
			} else {
				if ($interpro && $interpro->email_contrat_vrac) {
					Email::getInstance()->vracDemandeAnnulationInterpro($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
				}
			}
		}
	}
	

	
	protected function contratRefusAnnulation($vrac, $interpro, $etab = null) {
		$acteurs = VracClient::getInstance()->getActeurs();
		if (!$vrac->mandataire_exist) {
			unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
		}
		if ($etab) {
			unset($acteurs[array_search($vrac->getTypeByEtablissement($etab->identifiant), $acteurs)]);
		}
		foreach ($acteurs as $acteur) {
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			if ($compte && $compte->email) {
				if ($compte->statut == _Compte::STATUT_ARCHIVE) {
					if ($interpro && $interpro->email_contrat_vrac) {
						Email::getInstance()->vracRefusAnnulation($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
					}
				} else {
					Email::getInstance()->vracRefusAnnulation($vrac, $etab, $etablissement, $compte->email, $acteur);
				}
			} else {
				if ($interpro && $interpro->email_contrat_vrac) {
					Email::getInstance()->vracRefusAnnulation($vrac, $etab, $etablissement, $interpro->email_contrat_vrac, $acteur);
				}
			}
		}
	}

}