<?php

class CampagneManager {

	protected $mm_dd_debut;

	public function __construct($mm_dd_debut) {
		$this->mm_dd_debut = $mm_dd_debut;
	}

	public function getCampagneByDate($date) {

        return sprintf('%s-%s', date('Y', strtotime($this->getDateDebutByDate($date))), date('Y', strtotime($this->getDateFinByDate($date))));
    }

    public function getCurrent() {

        return $this->getCampagneByDate(date('Y-m-d'));
    }

    public function getDateDebutByCampagne($campagne) {
        $annees = $this->getAnnees($campagne);

        return $annees[1]."-".$this->mm_dd_debut; 
    }

    public function getDateFinByCampagne($campagne) {
        $date_debut = new DateTime($this->getDateDebutByCampagne($campagne));

        return $date_debut->modify("+1 year")->modify("-1 day")->format('Y-m-d');
    }

    public function getDateDebutByDate($date) {
        $annee = date('Y', strtotime($date));

        while($date < $annee."-".$this->mm_dd_debut) {
        	$annee = $annee - 1;
        }

        return $annee."-".$this->mm_dd_debut;
    }

    public function getDateFinByDate($date) {
    	$date_debut = new DateTime($this->getDateDebutByDate($date));

    	return $date_debut->modify("+1 year")->modify("-1 day")->format('Y-m-d');
    }

    public function getPrevious($campagne) {
        $annees = $this->getAnnees();

        return sprintf('%s-%s', $annees[1]-1, $annees[2]-1); 

    }

    public function getNext($campagne) {
        $annees = $this->getAnnees();

        return sprintf('%s-%s', $annees[1]+1, $annees[2]+1); 

    }

    protected function getAnnees($campagne) {
    	if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return $annees;
    }

}