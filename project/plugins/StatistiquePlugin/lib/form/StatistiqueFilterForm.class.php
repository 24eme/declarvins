<?php
class StatistiqueFilterForm extends BaseForm
{
	protected $interpro;
	
	public function __construct($interpro, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro = $interpro;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}
  	
	public function configure() 
	{
	        // DECLARER LES FILTRES EN COMMUN
        
        $this->widgetSchema->setNameFormat('statistique_filter[%s]');
    }
    
    public function getInterproId()
    {
    	return $this->interpro->get('_id');
    }
    
    public function getDepartementsChoices()
    {
    	$departements = array();
    	foreach ($this->interpro->departements as $departement) {
    		$dep = sprintf('%02d', $departement);
    		$departements[$dep.'*'] = $dep;
    	}
    	return $departements;
    }
}