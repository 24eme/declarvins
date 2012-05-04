<?php
class VracHistorique
{
	const VIEW_INDEX_ETABLISSEMENT = 0;
	const VIEW_INDEX_NUMERO = 1;
	const VIEW_INDEX_ACTIF = 2;
	const VIEW_INDEX_ANNEE = 3;
	const VIEW_INDEX_MOIS = 4;
	const VIEW_INDEX_DATE_CREATION = 5;
	const VIEW_INDEX_PRODUIT = 6;
	const VIEW_INDEX_VOLUME = 7;
	const VIEW_INDEX_ACHETEUR = 8;
	const VIEW_INDEX_COURTIER = 9;
	const VIEW_INDEX_ID = 10;
	const CAMPAGNE = 'campagne';

	private $etablissement;
	private $campagneCourante;
	private $vrac;
	private $campagnes;
	
	public function __construct($etablissement, $campagneCourante = null)
	{
		$this->etablissement = $etablissement;
		$this->campagneCourante = $campagneCourante;
	}
	
	public function getSliceVrac($limit = 0) {
		return array_slice($this->getVrac(), 0, $limit);
	}
	
	public function getVrac($limite = 0)
	{
		if (!$this->vrac) {
			$this->loadVrac();
		}
		return $this->vrac;
	}
	
	private function loadVrac()
	{
		$vracs = acCouchdbManager::getClient()
						->startkey(array($this->etablissement, null))
    					->endkey(array($this->etablissement, array()))
    					->getView("vrac", "all")
    					->rows;

		$result = array();
		foreach ($vracs as $vrac) {
			$values = $vrac->key;
			$values[self::VIEW_INDEX_ID] = $vrac->id;
			$values[self::CAMPAGNE] = $this->makeCampagne($vrac->key[self::VIEW_INDEX_ANNEE], $vrac->key[self::VIEW_INDEX_MOIS]);
		  	$result[str_replace('-', '', $vrac->key[self::VIEW_INDEX_DATE_CREATION])] = $values;
		}
		krsort($result);
		$this->vrac = $result;
	}
	
	public function getCampagnes()
	{
		if (!$this->campagnes) {
			$campagnes = array();
			$vracs = $this->getVrac();
	    	foreach ($vracs as $vrac) {
		  	if (!in_array($vrac[self::CAMPAGNE], $campagnes)) {
	  				$campagnes[] = $vrac[self::CAMPAGNE];
	    		}
	  		}
	  		rsort($campagnes);
	  		$this->campagnes = $campagnes;
		}
		return $this->campagnes;
	}
	
	private function makeCampagne($annee, $mois) {
		if ($annee.$mois < $annee.'08') {
			return ($annee-1).'-'.$annee;
		} else {
			return $annee.'-'.($annee+1);
		}
	}
	
	public function getVracParCampagneCourante()
	{
		$vracCampagne = array();
		$campagneCourante = $this->getCampagneCourante();
		$vracs = $this->getVrac();
		foreach ($vracs as $id => $vrac) {
			if ($vrac[self::CAMPAGNE] == $campagneCourante) {
				$vracCampagne[] = $vrac;
			}
		}
		return $vracCampagne;
	}
	
	public function getVracActif()
	{
		$vracActif = array();
		$vracs = $this->getVrac();
		foreach ($vracs as $id => $vrac) {
			if ($vrac[self::VIEW_INDEX_ACTIF]) {
				$vracActif[] = $vrac;
			}
		}
		return $vracActif;
	}
	
	public function getCampagneCourante()
	{
		if (!$this->campagneCourante) {
			if($campagnes = $this->getCampagnes()) {
				$this->campagneCourante = $campagnes[0];
			}
		}
		return $this->campagneCourante;
	}

	public function getLastVrac()
	{
		return $this->getSliceVrac(1);
	}
}