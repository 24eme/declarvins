<?php

class DAIDSDetailCvoForm extends acCouchdbObjectForm 
{
    public function configure() 
    {
    	$this->setWidget('total_manquants_taxables_cvo', new sfWidgetFormInputFloat());
    	$this->setValidator('total_manquants_taxables_cvo', new sfValidatorNumber(array('required' => true)));
        $this->widgetSchema->setLabel('total_manquants_taxables_cvo', 'Volumes manquants cotisables DAI/DS: ');
        $this->widgetSchema->setNameFormat('daids_detail_cvo[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function doUpdateObject($values) 
    {
    	parent::doUpdateObject($values);
        $this->getObject()->updateCvo(array('cvo_manuel' => true));
        $this->getObject()->getDocument()->updateCvo();
    }
}