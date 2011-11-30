<?php

class DRMDetailForm extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'denomination' => new sfWidgetFormInputText(array()),
            'couleur'      => new sfWidgetFormInputText(array()),
            'label'        => new sfWidgetFormInputText(array()),
        ));

        $this->setValidators(array(
            'denomination' => new sfValidatorString(array('required' => true)),
            'couleur'      => new sfValidatorString(array('required' => true)),
            'label'        => new sfValidatorString(array('required' => false)),
        ));

        $this->stocks = new DRMDetailStocksForm($this->getObject()->stocks);
        $this->embedForm('stocks', $this->stocks);
            
        $this->entrees = new DRMDetailEntreesForm($this->getObject()->entrees);
        $this->embedForm('entrees', $this->entrees);

        $this->sorties = new DRMDetailEntreesForm($this->getObject()->sorties);
        $this->embedForm('sorties', $this->sorties);
        
        $this->widgetSchema->setNameFormat('drm_detail[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}