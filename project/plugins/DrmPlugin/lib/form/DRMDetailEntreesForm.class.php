<?php

class DRMDetailEntreesForm  extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'nouveau'    => new sfWidgetFormInputFloat(),
            'repli' => new sfWidgetFormInputFloat(),
            'declassement' => new sfWidgetFormInputFloat(),
            'mouvement'  => new sfWidgetFormInputFloat(),
            'crd' => new sfWidgetFormInputFloat(),
        ));

        $this->setValidators(array(
            'nouveau' => new sfValidatorNumber(array('required' => false)),
            'repli'    => new sfValidatorNumber(array('required' => false)),
            'declassement'    => new sfValidatorNumber(array('required' => false)),
            'mouvement'  => new sfValidatorNumber(array('required' => false)),
            'crd'  => new sfValidatorNumber(array('required' => false)),
        ));
        
        $this->widgetSchema->setNameFormat('drm_detail_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}