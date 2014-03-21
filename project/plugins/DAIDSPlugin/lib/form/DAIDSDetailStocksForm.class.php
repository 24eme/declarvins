<?php

class DAIDSDetailStocksForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->setWidget('chais', new sfWidgetFormInputHidden());
    	$this->setWidget('propriete_tiers', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setWidget('tiers', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setValidator('chais', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('propriete_tiers', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('tiers', new sfValidatorNumber(array('required' => false)));
    	
    	/*
    	 * CHAMPS FICTIFS
    	 */
    	$this->setWidget('inventaire_chais', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setWidget('physique_chais', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setValidator('inventaire_chais', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('physique_chais', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('stocks[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    protected function updateDefaultsFromObject() 
    {
      parent::updateDefaultsFromObject();  
      $this->setDefault('inventaire_chais', $this->getObject()->chais);
      $this->setDefault('physique_chais', $this->getObject()->chais);
    }

}