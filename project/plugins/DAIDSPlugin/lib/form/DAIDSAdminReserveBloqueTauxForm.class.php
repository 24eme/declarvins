<?php
class DAIDSAdminReserveBloqueTauxForm extends acCouchdbObjectForm 
{
    public function configure() 
    {
    	$this->setWidget('taux', new sfWidgetFormInputFloat());
    	$this->setValidator('taux', new sfValidatorNumber());
        
        $this->widgetSchema->setNameFormat('configuration_daids_reserve_bloque[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}