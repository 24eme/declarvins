<?php
class DRMsManquantes 
{
	protected $etablissements;
	const REGEXP_CAMPAGNE = '#^[0-9]{4}-[0-9]{4}$#';
	const CAMPAGNE_DELIMITER = '-';
	const START_MONTH_CAMPAGNE = '08';
	const END_MONTH_CAMPAGNE = '07';
	const ALERTE_DOC_ID = 'DRMMANQUANTE';
	
	public function __construct()
	{
		$this->etablissements = AlertesEtablissementsView::getInstance()->findActive();
	}
	
	protected function getCampagneFormatted($campagne)
	{
		if (!preg_match(self::REGEXP_CAMPAGNE, $campagne)) {
			throw new sfException('La campagne doit être au format AAAA-AAAA');
		}
		$explosedCampagne = explode(self::CAMPAGNE_DELIMITER, $campagne);
		if (($explosedCampagne[0] + 1) != $explosedCampagne[1]) {
			throw new sfException('La campagne n\'est pas valide');
		}
		return $campagne;
	}
	
	public function getAlertes($campagne = null)
	{
		if ($campagne) {
			return $this->getAlertesByCampagne($campagne);
		}
		$alertes = array();
		$drms = AlertesDrmsView::getInstance()->findAll();
		if (count($drms->rows) > 0) {
			$maxCampagne = null;
			$minCampagne = null;
			foreach ($drms->rows as $drm) {
				if (!$maxCampagne && !$minCampagne) {
					$maxAnnee = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
					$minCampagne = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
				}
				if ($drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS] < $minCampagne) {
					$minCampagne = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
				}
				if ($drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS] > $maxCampagne) {
					$maxCampagne = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
				}
			}
			$campagnes = $this->getIntervalleCampagne($this->getCampagneByDateDrm(substr($minCampagne, 0, 4), substr($minCampagne, -2)), $this->getCampagneByDateDrm(substr($maxCampagne, 0, 4), substr($maxCampagne, -2)));
			$nbCampagnes = count($campagnes);
			$counter = 0;
			foreach ($campagnes as $campagne) {
				$counter++;
				$alertes = array_merge($this->getAlertesByCampagne($campagne, ($nbCampagnes == $counter)), $alertes);
			}
		}
		return $alertes;
	}
	protected function getIntervalleCampagne($minCampagne, $maxCampagne)
	{
		$campagnes = array();
		$explosedMinCampagne = explode(self::CAMPAGNE_DELIMITER, $minCampagne);
		$explosedMaxCampagne = explode(self::CAMPAGNE_DELIMITER, $maxCampagne);
		for ($campagne = $explosedMinCampagne[0]; $campagne < $explosedMaxCampagne[1]; $campagne++) {
			$campagnes[] = $campagne.self::CAMPAGNE_DELIMITER.($campagne+1);
		}
		return $campagnes;
	}
	protected function getCampagneByDateDrm($year_drm, $month_drm) 
	{
		if ($month_drm >= self::START_MONTH_CAMPAGNE) {
			return $year_drm.self::CAMPAGNE_DELIMITER.($year_drm + 1);
		} else {
			return ($year_drm - 1).self::CAMPAGNE_DELIMITER.$year_drm;
		}
	}
	
	
	public function getAlertesByCampagne($campagne, $lastCampagne = false)
	{
		$campagne = $this->getCampagneFormatted($campagne);
		$years = explode(self::CAMPAGNE_DELIMITER, $campagne);
		$campagne_year_start = $years[0];
		$campagne_year_end = $years[1];
		$campagne_month_start = self::START_MONTH_CAMPAGNE;
		$campagne_month_end = self::END_MONTH_CAMPAGNE;
		$alertes = array();
		$campagnesCompletes = $this->getCampagnesCompletes($campagne_year_start, $campagne_month_start, $campagne_year_end, $campagne_month_end);
		foreach ($this->etablissements->rows as $etablissement) {
			$identifiant = $etablissement->key[AlertesEtablissementsView::KEY_IDENTIFIANT];
			$drms = AlertesDrmsView::getInstance()->findByCampagneAndEtablissement($campagne_year_start, $campagne_month_start, $campagne_year_end, $campagne_month_end, $identifiant);
			if (count($drms->rows) > 0) {
				$campagnes = array();
				foreach ($drms->rows as $drm) {
					$c = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
					if (!in_array($c, $campagnes)) {
						$campagnes[] = $c;
					}
				}
				rsort($campagnes);
				$drmsManquantes = $this->getDrmsManquantes($campagnesCompletes, $campagnes, $lastCampagne);
				foreach ($drmsManquantes as $drmsManquante) {
					$docId = self::ALERTE_DOC_ID.'-'.$identifiant.'-'.$this->makeCampagne($drmsManquante);
					$alertes[$docId] = $this->genereAlerte($etablissement, $this->makeCampagne($drmsManquante));
				}
			} else {
				foreach ($campagnesCompletes as $campagneManquante) {
					$docId = self::ALERTE_DOC_ID.'-'.$identifiant.'-'.$this->makeCampagne($campagneManquante);
					$alertes[$docId] = $this->genereAlerte($etablissement, $this->makeCampagne($campagneManquante));
				}
			}
		}
		return $alertes;
	}
	
	private function makeCampagne($campagne) {
		return substr($campagne, 0, 4).'-'.substr($campagne, -2); 
	}
	
	private function genereAlerte($etablissement, $campagne) {
		$identifiant = $etablissement->key[AlertesEtablissementsView::KEY_IDENTIFIANT];
		$docId = self::ALERTE_DOC_ID.'-'.$identifiant.'-'.$campagne;
		$alerte = AlerteClient::getInstance()->find($docId);
		if (!$alerte) {
			$alerte = new Alerte($docId, $campagne);
			$alerte->interpro = $etablissement->value;
			$alerte->etablissement_identifiant = $identifiant;
			$alerte->sous_type = self::ALERTE_DOC_ID;
			$newAlerte = $alerte->alertes->getOrAdd(date('c'));
			$newAlerte->statut = Alerte::STATUT_ACTIF;
			$newAlerte->detail = 'DRM-'.$identifiant.'-'.$campagne.' manquante';
		} else {
			$lastAlerte = $alerte->getLastAlerte();
			if ($lastAlerte->statut != Alerte::STATUT_ACTIF) {
				$newAlerte = $alerte->alertes->getOrAdd(date('c'));
				$newAlerte->statut = Alerte::STATUT_ACTIF;
				$newAlerte->detail = 'DRM-'.$identifiant.'-'.$campagne.' manquante';
			}
		}
		return $alerte;
	}
	
	private function getCampagnesCompletes($year_start, $month_start, $year_end, $month_end)
	{
		$campagnes = array();
		for ($i = $month_start; $i <= 12; $i++) {
			$month = sprintf('%02d', $i);
			$campagnes[] = $year_start.$month;
		}
		for ($i = 1; $i <= $month_end; $i++) {
			$month = sprintf('%02d', $i);
			$campagnes[] = $year_end.$month;
		}
		rsort($campagnes);
		return $campagnes;
	}
	
	private function getDrmsManquantes($campagnesCompletes, $campagnes, $lastCampagne)
	{
		$lastDrm = $campagnes[0];
		foreach ($campagnesCompletes as $key => $campagne) {
			if ($lastCampagne && $campagne > $lastDrm) {
				unset($campagnesCompletes[$key]);
			}
		}
		return array_diff($campagnesCompletes, $campagnes);
	}
	

}