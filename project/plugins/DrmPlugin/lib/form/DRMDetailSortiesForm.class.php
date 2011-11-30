<?php

class DRMDetailSortiesForm  extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'vrac'         => new sfWidgetFormInputFloat(),
            'export'       => new sfWidgetFormInputFloat(),
            'factures'     => new sfWidgetFormInputFloat(),
            'crd'          => new sfWidgetFormInputFloat(),
            'consommation' => new sfWidgetFormInputFloat(),
            'pertes'       => new sfWidgetFormInputFloat(),
            'declassement' => new sfWidgetFormInputFloat(),
            'repli'        => new sfWidgetFormInputFloat(),
            'mouvement'    => new sfWidgetFormInputFloat(),
            'lies'         => new sfWidgetFormInputFloat(),
        ));

        $this->setValidators(array(
            'vrac'      => new sfValidatorNumber(array('required' => false)),
            'export'    => new sfValidatorNumber(array('required' => false)),
            'factures'  => new sfValidatorNumber(array('required' => false)),
            'crd'      => new sfValidatorNumber(array('required' => false)),
            'consommation'    => new sfValidatorNumber(array('required' => false)),
            'pertes'  => new sfValidatorNumber(array('required' => false)),
            'declassement'      => new sfValidatorNumber(array('required' => false)),
            'repli'    => new sfValidatorNumber(array('required' => false)),
            'mouvement'  => new sfValidatorNumber(array('required' => false)),
            'lies'      => new sfValidatorNumber(array('required' => false)),
        ));
        
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}