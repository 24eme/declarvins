<?php
class StatistiqueProduitForm extends BaseForm
{
	protected $interpro;
	
	public function __construct($interpro, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro = $interpro;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}
	
	public function configure() 
	{
		$choices = array_merge(array(''=>''), $this->getProduits());
        $this->setWidget('declaration', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('declaration', 'Produit :');
        $this->setValidator('declaration', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        
    }
	
    protected function getProduits()
    {
    	$configurationProduits = ConfigurationClient::getCurrent()->getConfigurationProduits($this->interpro);
    	return $configurationProduits->getTreeProduits();
    }
}