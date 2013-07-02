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
    
    protected function getDepartementsChoices()
    {
    	$departements = array();
    	foreach ($this->interpro->departements as $departement) {
    		$dep = sprintf('%02d', $departement);
    		$departements[$dep.'*'] = $dep;
    	}
    	return $departements;
    }
    
    protected function getDouanes() 
    {
        $douanes = DouaneAllView::getInstance()->findActives()->rows;
        $choices = array();
        foreach ($douanes as $douane) {
        	$value = $douane->value;
            $choices[$value[DouaneAllView::VALUE_DOUANE_NOM]] = $value[DouaneAllView::VALUE_DOUANE_NOM];
        }
        return $choices;
    }
    protected function getFamilles() 
    {
        $familles = EtablissementFamilles::getFamilles();
        $choices = array();
        foreach ($familles as $key => $famille) {
            $choices[$key] = $famille;
        }
        return $choices;
    }
    
	protected function getSousFamilles() 
	{
    	$sousFamilles =  EtablissementFamilles::getSousFamillesAll();	
    	$result = array();
    	foreach ($sousFamilles as $key => $sousFamille) {
    			$result[$key] = $sousFamille;
    	}
    	return $result;
    }
    
    protected function getModesDeSaisie()
    {
    	return DRMClient::getInstance()->getModesDeSaisie();
    }

    
    protected function getCampagneChoices()
    {
    	$years = range(date('Y'), date('Y') - 20);
    	$choices = array();
    	foreach ($years as $year) {
    		$campagne = ($year-1).'-'.$year;
    		$choices[$campagne] = $campagne;
    	}
    	return $choices;
    }
    
    protected function getProduits()
    {
    	return ConfigurationClient::getInstance()->findTreeProduitsLibelleForAdmin($this->getInterproId(), false);
    }
}