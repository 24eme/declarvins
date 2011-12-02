<?php

class DRMDetailStocksForm  extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'theorique' => new sfWidgetFormInputFloat(),
            'bloque'    => new sfWidgetFormInputFloat(),
            'warrante'  => new sfWidgetFormInputFloat(),
            'instance'  => new sfWidgetFormInputFloat(),
        ));

        $this->setValidators(array(
            'theorique' => new sfValidatorNumber(array('required' => false)),
            'bloque'    => new sfValidatorNumber(array('required' => false)),
            'warrante'  => new sfValidatorNumber(array('required' => false)),
            'instance'  => new sfValidatorNumber(array('required' => false)),
        ));
        
        $this->widgetSchema->setNameFormat('drm_detail_stocks[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}