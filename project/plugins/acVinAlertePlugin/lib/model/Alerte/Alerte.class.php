<?php
class Alerte extends BaseAlerte 
{
	protected $id;
	protected $camp;
	const STATUT_ACTIF = 'ACTIVE';
	const STATUT_TRAITE = 'TRAITE';
	const STATUT_RESOLU = 'RESOLU';
	const STATUT_REJETE = 'REJETE';
	const CAMPAGNE_MOIS_DEBUT = '08'; 
	
	protected static $_statuts = array(
		self::STATUT_ACTIF => 'Actif',
		self::STATUT_TRAITE => 'Traité',
		self::STATUT_RESOLU => 'Résolu',
		self::STATUT_REJETE => 'Rejeté',
	);
	
	public function __construct($id = null, $campagne = null) 
	{
        parent::__construct();
        $this->id = $id;
        $this->camp = $campagne;
    }
    
    public function constructId() 
    {
        $this->set('_id', $this->id);
    }
    
    public function save() 
    {
        $this->derniere_detection = date('c');
        if ($this->isNew()) {
        	$this->campagne = $this->getFormattedCampagne();
        }
        parent::save();
    }
    
    public function getFormattedCampagne() 
    {
    	$campagne = explode('-', $this->camp);
    	$year = $campagne[0];
    	if ($campagne[1] >= self::CAMPAGNE_MOIS_DEBUT) {
    		return $year.'-'.($year+1);
    	} else {
    		return ($year-1).'-'.$year;
    	}
    }
    
    public function getLastAlerte() 
    {
    	return $this->alertes->getLast();
    }
    
    public static function getStatuts()
    {
    	return self::$_statuts;
    }

}