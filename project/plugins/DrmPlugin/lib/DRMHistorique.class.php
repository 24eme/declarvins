<?php
class DRMHistorique
{
	private $etablissement;
	private $anneeCourante;
	private $drms;
	private $annees;
	
	public function __construct($etablissement, $anneeCourante = null)
	{
		$this->etablissement = $etablissement;
		$this->anneeCourante = $anneeCourante;
		$this->loadDrms();
	}
	
	private function loadDrms()
	{
		$this->drms = acCouchdbManager::getClient()
						->startkey(array($this->etablissement, null))
    					->endkey(array($this->etablissement, array()))
    					->group(true)
    					->getView("drm", "all")
    					->rows;
	}
	
	public function getAnnees()
	{
		if (!$this->annees) {
			$annees = array();
	    	foreach ($this->drms as $drm) {
	  			$annees[] = $drm->key[1];
	  		}
	  		rsort($annees);
	  		$this->annees = $annees;
		}
		return $this->annees;
	}
	
	public function getDrmsParAnneeCourante()
	{
		$drms = array();
		$anneeCourante = $this->getAnneeCourante();
		foreach ($this->drms as $drm) {
			if ($drm->key[1] == $anneeCourante) {
				$drms['DRM-'.$drm->key[0].'-'.$drm->key[1].'-'.$drm->key[2]] = $drm->key;
			}
		}
		krsort($drms);
		return $drms;
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
}