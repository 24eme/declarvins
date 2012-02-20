<?php

class DRMDetailStocksForm  extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'bloque'    => new sfWidgetFormInputFloat(),
            'warrante'  => new sfWidgetFormInputFloat(),
            'instance'  => new sfWidgetFormInputFloat(),
        ));

        $this->setValidators(array(
            'bloque'    => new sfValidatorNumber(array('required' => false)),
            'warrante'  => new sfValidatorNumber(array('required' => false)),
            'instance'  => new sfValidatorNumber(array('required' => false)),
        ));
        
        $this->widgetSchema->setNameFormat('drm_detail_stocks_fin[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}