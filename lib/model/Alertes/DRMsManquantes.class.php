<?php
class DRMsManquantes 
{
	protected $campagne;
	protected $campagne_year_start;
	protected $campagne_month_start;
	protected $campagne_year_end;
	protected $campagne_month_end;
	protected $etablissements;
	const REGEXP_CAMPAGNE = '#^[0-9]{4}-[0-9]{4}$#';
	const CAMPAGNE_DELIMITER = '-';
	const START_MONTH_CAMPAGNE = '08';
	const END_MONTH_CAMPAGNE = '07';
	
	const ALERTE_DOC_ID = 'DRMMANQUANTE';
	
	public function __construct($campagne = null)
	{
		$this->campagne = $this->getCampagneFormatted($campagne);
		$this->setExplodedCampagneFormat();
		$this->etablissements = AlertesEtablissementsView::getInstance()->findActive();
	}
	
	public function getCampagneFormatted($campagne)
	{
		if (!preg_match(self::REGEXP_CAMPAGNE, $campagne)) {
			throw new sfException('La campagne doit être au format AAAA-AAAA');
		}
		return $campagne;
	}
	
	private function setExplodedCampagneFormat()
	{
		$years = explode(self::CAMPAGNE_DELIMITER, $this->campagne);
		$this->campagne_year_start = $years[0];
		$this->campagne_year_end = $years[1];
		$this->campagne_month_start = self::START_MONTH_CAMPAGNE;
		$this->campagne_month_end = self::END_MONTH_CAMPAGNE;
	}
	
	public function getAlertes()
	{
		$alertes = array();
		$campagnesCompletes = $this->getCampagnesCompletes($this->campagne_year_start, $this->campagne_month_start, $this->campagne_year_end, $this->campagne_month_end);
		foreach ($this->etablissements->rows as $etablissement) {
			$identifiant = $etablissement->key[AlertesEtablissementsView::KEY_IDENTIFIANT];
			$drms = AlertesDrmsView::getInstance()->findByCampagneAndEtablissement($this->campagne_year_start, $this->campagne_month_start, $this->campagne_year_end, $this->campagne_month_end, $identifiant);
			if (count($drms->rows) > 0) {
				$campagnes = array();
				foreach ($drms->rows as $drm) {
					$campagne = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
					$campagnes[] = $campagne;
				}
				rsort($campagnes);
				$drmsManquantes = $this->getDrmsManquantes($campagnesCompletes, $campagnes);
				foreach ($drmsManquantes as $drmsManquante) {
					$alertes[] = $this->genereAlerte($etablissement, $this->makeCampagne($drmsManquante));
				}
			} else {
				foreach ($campagnesCompletes as $campagneManquante) {
					$alertes[] = $this->genereAlerte($etablissement, $this->makeCampagne($campagneManquante));
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
	
	private function getDrmsManquantes($campagnesCompletes, $campagnes)
	{
		$lastDrm = $campagnes[0];
		foreach ($campagnesCompletes as $key => $campagne) {
			if ($campagne > $lastDrm) {
				unset($campagnesCompletes[$key]);
			}
		}
		return array_diff($campagnesCompletes, $campagnes);
	}
	

}