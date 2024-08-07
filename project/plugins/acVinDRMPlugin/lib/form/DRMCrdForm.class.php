<?php

class DRMCrdForm extends acCouchdbObjectForm
{

    public function configure()
    {
    		$this->setWidget('total_debut_mois', new sfWidgetFormInput([], array('readonly' => 'readonly')));
    		$this->setValidator('total_debut_mois', new sfValidatorInteger(array('required' => false)));


    	$this->entrees = new DRMCrdEntreesForm($this->getObject()->entrees);
    	$this->embedForm('entrees', $this->entrees);

    	$this->sorties = new DRMCrdSortiesForm($this->getObject()->sorties);
    	$this->embedForm('sorties', $this->sorties);

        $this->widgetSchema->setNameFormat('[%s]');
    }


    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->updateStocks();
    }
    

    
}