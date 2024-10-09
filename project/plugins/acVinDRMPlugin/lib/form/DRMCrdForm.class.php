<?php

class DRMCrdForm extends acCouchdbObjectForm
{

    public function configure()
    {
        $doc = $this->getObject()->getDocument();
        $etablissement = $doc->getEtablissement();
        $mois = DateTime::createFromFormat('Y-m', $doc->periode)->format('m');
        $options = ['readonly' => 'readonly'];

        if ($etablissement->getMoisToSetStock() == $mois) {
            unset($options['readonly']);
        }

        $this->setWidget('total_debut_mois', new sfWidgetFormInput([], $options));
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