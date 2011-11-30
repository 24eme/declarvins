<?php

class DRMDetailEntreesForm  extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'nouveau'    => new sfWidgetFormInputFloat(),
            'changement' => new sfWidgetFormInputFloat(),
            'mouvement'  => new sfWidgetFormInputFloat(),
        ));

        $this->setValidators(array(
            'nouveau' => new sfValidatorNumber(array('required' => false)),
            'changement'    => new sfValidatorNumber(array('required' => false)),
            'mouvement'  => new sfValidatorNumber(array('required' => false)),
        ));
        
        $this->widgetSchema->setNameFormat('drm_detail_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}