<?php
class VracHistorique
{
	const VIEW_INDEX_ETABLISSEMENT = 0;
	const VIEW_INDEX_NUMERO = 1;
	const VIEW_INDEX_ACTIF = 2;
	const VIEW_INDEX_ANNEE = 3;
	const VIEW_INDEX_DATE_CREATION = 4;
	const VIEW_INDEX_PRODUIT = 5;
	const VIEW_INDEX_VOLUME = 6;
	const VIEW_INDEX_ACHETEUR = 7;
	const VIEW_INDEX_COURTIER = 8;
	const VIEW_INDEX_ID = 9;

	private $etablissement;
	private $anneeCourante;
	private $vrac;
	private $annees;
	
	public function __construct($etablissement, $anneeCourante = null)
	{
		$this->etablissement = $etablissement;
		$this->anneeCourante = $anneeCourante;
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
			$date = explode('/', $vrac->key[self::VIEW_INDEX_DATE_CREATION]);
			$values = $vrac->key;
			$values[self::VIEW_INDEX_ID] = $vrac->id;
		  	$result[$date[2].$date[1].$date[0]] = $values;
		}
		krsort($result);
		$this->vrac = $result;
	}
	
	public function getAnnees()
	{
		if (!$this->annees) {
			$annees = array();
			$vracs = $this->getVrac();
	    	foreach ($vracs as $vrac) {
		  	if (!in_array($vrac[self::VIEW_INDEX_ANNEE], $annees)) {
	  				$annees[] = $vrac[self::VIEW_INDEX_ANNEE];
	    		}
	  		}
	  		rsort($annees);
	  		$this->annees = $annees;
		}
		return $this->annees;
	}
	
	public function getVracParAnneeCourante()
	{
		$vracAnnee = array();
		$anneeCourante = $this->getAnneeCourante();
		$vracs = $this->getVrac();
		foreach ($vracs as $id => $vrac) {
			if ($vrac[self::VIEW_INDEX_ANNEE] == $anneeCourante) {
				$vracAnnee[] = $vrac;
			}
		}
		return $vracAnnee;
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
	
	public function getAnneeCourante()
	{
		if (!$this->anneeCourante) {
			if($annees = $this->getAnnees()) {
				$this->anneeCourante = $annees[0];
			}
		}
		return $this->anneeCourante;
	}

	public function getLastVrac()
	{
		return $this->getSliceVrac(1);
	}
}