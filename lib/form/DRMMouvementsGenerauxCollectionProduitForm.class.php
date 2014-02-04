<?php
class DRMMouvementsGenerauxCollectionProduitForm extends sfForm
{
	protected $details;
	
	public function __construct($details, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->details = $details;
		parent::__construct($defaults, $options, $CSRFSecret);
  	}

	public function configure() 
	{
        foreach ($this->details as $detail) {
            if($detail->getDroit(ConfigurationProduit::NOEUD_DROIT_CVO)->taux >= 0){
        	$this->embedForm($detail->getHash(), new DRMMouvementsGenerauxProduitForm($detail));
            }
        }
    }

    public function doUpdateObject($values) {
        foreach ($this->getEmbeddedForms() as $key => $embedForm) {
        	$embedForm->doUpdateObject($values[$key]);
        }
    }

}