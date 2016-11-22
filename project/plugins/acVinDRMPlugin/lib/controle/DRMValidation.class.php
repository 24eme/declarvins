<?php
class DRMValidation
{
	private $drm;
	private $engagements;
	private $warnings;
	private $errors;
	private $isAdmin;
	private $isCiel;
	const VINSSANSIG_KEY = 'VINSSANSIG';
	const VCI_KEY = 'VCI';
	const AOP_KEY = 'AOP';
	const IGP_KEY = 'IGP';
	const NO_LINK = '#';
	const ECART_VRAC = 0.2;
	
	public function __construct($drm, $options = null)
	{
		$this->drm = $drm;
		$this->options = $options;
		$this->engagements = array();
		$this->warnings = array();
		$this->errors = array();
		$etablissement = $drm->getEtablissementObject();
		$compte = $etablissement->getCompteObject();
		$this->isAdmin = ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER)? true : false;
		$this->isCiel = ($compte && $compte->exist('dematerialise_ciel') && $compte->dematerialise_ciel)? true : false;
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
				if ($certification->getKey() == self::AOP_KEY && $detail->sorties->repli) {
					$this->engagements['odg'] = new DRMControleEngagement('odg');
				}
				if ($certification->getKey() == self::IGP_KEY && $detail->entrees->declassement) {
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
			if (round($totalEntreeRepli,4) != round($totalSortiRepli,4) && !$this->isAdmin) {
				$this->errors['repli_'.$certification->getKey()] = new DRMControleError('repli', $this->generateUrl('drm_recap', $certification));
			}
		}
		if (round($totalEntreeDeclassement,4) > round($totalSortiDeclassement,4) && !$this->isAdmin) {
			$this->errors['declassement_'.self::VINSSANSIG_KEY] = new DRMControleError('declassement', $this->generateUrl('drm_recap', $certificationVinssansig));
		}
		if (round($totalVciEntree,4) != round($totalSortiVci,4) || round($totalVciSorti,4) != round($totalEntreeVci,4)) {
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
	}
	
	public function isValide() {
	  return !($this->hasErrors());
	}
	
	private function controleEngagements($detail)
	{
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
		if (round($detail->total,4) < round($detail->stocks_fin->bloque + $detail->stocks_fin->instance,4)) {
			$this->errors['total_stocks_'.$detail->getIdentifiantHTML()] = new DRMControleError('total_stocks', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
		}
		if (isset($this->options['is_operateur']) && !$this->options['is_operateur']) {
			if (!isset($this->options['no_vrac']) || ! $this->options['no_vrac']) {
			  foreach ($detail->vrac as $contrat) {
			    $totalVolume += $contrat->volume;
			  }
			  if (($detail->canHaveVrac() && $detail->sorties->vrac) || count($detail->vrac->toArray()) > 0) {
			  	  $ecart = round($detail->sorties->vrac * self::ECART_VRAC, 4);
				  if (round($totalVolume,4) < (round($detail->sorties->vrac,4) - $ecart) || round($totalVolume,4) > (round($detail->sorties->vrac,4) + $ecart)) {
				  	if ($detail->getCertification()->getKey() == self::IGP_KEY || $detail->interpro == 'INTERPRO-CIVP') {
				  		$this->warnings['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': %message%');
				    } else {
				    	$this->errors['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleError('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': %message%');
				    }
				  }
			  }
			}
		}
		if ($drmSuivante = $this->drm->getSuivante()) {
			if ($drmSuivante->exist($detail->getHash())) {
				$d = $drmSuivante->get($detail->getHash());
				if (round($d->total_debut_mois,4) != round($detail->total,4) && !$this->drm->hasVersion()) {
					if(isset($this->options['stock']) && $this->options['stock'] == 'warning') {
						$this->warnings['stock_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('stock', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					} else {
						$this->errors['stock_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
					}
				}
			}
		}
		if ($drmPrecedente = $this->drm->getPrecedente()) {
			if ($drmPrecedente->exist($detail->getHash()) && !$this->drm->isDebutCampagne()) {
				$d = $drmPrecedente->get($detail->getHash());
				if (round($d->total,4) != round($detail->total_debut_mois,4)) {
					$this->errors['stock_deb_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock_deb', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
				}
				if (!$this->drm->canSetStockDebutMois(true) && round($d->acq_total,4) != round($detail->acq_total_debut_mois,4)) {
					$this->errors['stock_deb_'.$detail->getIdentifiantHTML()] = new DRMControleError('stock_deb_acq', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
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
			if (($detail->sorties->autres > 0 || $detail->sorties->pertes > 0) && !$detail->observations) {
				$this->errors['observations_autres_pertes_'.$detail->getIdentifiantHTML()] = new DRMControleError('obs_autres_pertes', $this->generateUrl('drm_declaratif', $this->drm), $detail->makeFormattedLibelle().': %message%');
			}
		}
		if ($detail->tav && ($detail->tav < 0.5 || $detail->tav > 100)) {
			$this->errors['tav_value_'.$detail->getIdentifiantHTML()] = new DRMControleError('tav_value', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
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
			  if (($detail->canHaveVrac() && $detail->sorties->vrac) || count($detail->vrac->toArray()) > 0) {
			  	  $ecart = round($detail->sorties->vrac * self::ECART_VRAC, 4);
				  if (round($totalVolume,4) < (round($detail->sorties->vrac,4) - $ecart) || round($totalVolume,4) > (round($detail->sorties->vrac,4) + $ecart)) {
				    $this->warnings['vrac_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('vrac', $this->generateUrl('drm_vrac', $this->drm), $detail->makeFormattedLibelle().': %message%');
				  }
			  }
			}
		}
		if ($detail->sorties->mouvement > 0) {
			$this->warnings['mouvement_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('mouvement', $this->generateUrl('drm_recap_detail', $detail).'#sorties', $detail->makeFormattedLibelle().': %message%');
		}
		/*if (!$detail->hasCvo() || !$detail->hasDouane()) {
			$this->warnings['droits_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('droits', $this->generateUrl('drm_recap_detail', $detail));
		}*/
		if (
			round($detail->total_debut_mois,4) < round($detail->stocks_debut->bloque,4) ||
			round($detail->total_debut_mois,4) < round($detail->stocks_debut->warrante,4) ||
			round($detail->total_debut_mois,4) < round($detail->stocks_debut->instance,4) ||
			round($detail->total_debut_mois,4) < round($detail->stocks_debut->commercialisable,4)
		) {
			$this->warnings['stocksdebut_'.$detail->getIdentifiantHTML()] = new DRMControleWarning('stocksdebut', $this->generateUrl('drm_recap_detail', $detail), $detail->makeFormattedLibelle().': %message%');
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
	
	public function hasError($error)
	{
		$keys = array_keys($this->errors);
    	return (count(preg_grep('/^'.$error.'_.+$/',$keys)) > 0);
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