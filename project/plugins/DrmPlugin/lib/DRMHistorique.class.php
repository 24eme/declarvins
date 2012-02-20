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
	}
	
	public function getSliceDrms($limit = 0) {
		return array_slice($this->getDrms(), 0, $limit);
	}
	
	public function getDrms($limite = 0)
	{
		if (!$this->drms) {
			$this->loadDrms();
		}
		return $this->drms;
	}
	
	private function loadDrms()
	{
		$drms = acCouchdbManager::getClient()
						->startkey(array($this->etablissement, null))
    					->endkey(array($this->etablissement, array()))
    					->group(true)
    					->getView("drm", "all")
    					->rows;
    	$result = array();
    	foreach ($drms as $drm) {
			$result['DRM-'.$drm->key[0].'-'.$drm->key[1].'-'.$drm->key[2]] = $drm->key;
		}
		krsort($result);
		$this->drms = $result;
	}
	
	public function getAnnees()
	{
		if (!$this->annees) {
			$annees = array();
			$drms = $this->getDrms();
	    	foreach ($drms as $drm) {
	    		if (!in_array($drm[1], $annees)) {
	  				$annees[] = $drm[1];
	    		}
	  		}
	  		rsort($annees);
	  		$this->annees = $annees;
		}
		return $this->annees;
	}
	
	public function getDrmsParAnneeCourante()
	{
		$drmsAnnee = array();
		$anneeCourante = $this->getAnneeCourante();
		$drms = $this->getDrms();
		foreach ($drms as $id => $drm) {
			if ($drm[1] == $anneeCourante) {
				$drmsAnnee[$id] = $drm;
			}
		}
		return $drmsAnnee;
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
	
	public function getFutureDrm()
	{
		$lastDrm = current($this->getLastDrm());
		$nextMonth = $lastDrm[2] + 1;
		if ($nextMonth < 10) {
			$nextMonth = '0'.$nextMonth;
		}
		$nextYear = $lastDrm[1];
		if ($nextMonth > 12) {
			$nextMonth = '01';
			$nextYear++;
		}
		return array('DRM-'.$this->etablissement.'-'.$nextYear.'-'.$nextMonth => array($this->etablissement, $nextYear, $nextMonth, 0, null, null));
	}
	
	public function getLastDrm()
	{
		return $this->getSliceDrms(1);
	}
	
	public function hasDrmInProcess()
	{
		$result = false;
		$drms = $this->getDrms();
		foreach ($drms as $drm) {
			if (!$drm[3]) {
				$result = true;
				break;
			}
		}
		return $result;
	}
}