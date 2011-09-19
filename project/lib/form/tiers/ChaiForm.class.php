<?php

class ChaiForm extends TiersForm {

    public function configure() {
        parent::configure();
        $this->setWidget('siret', new sfWidgetFormInputText(array('label' => 'Siret')));
        $this->setValidator('siret', new sfValidatorString(array('required' => true), array('required' => 'Champ Requis')));
        
        $this->widgetSchema->setNameFormat('chai[%s]');
        $this->mergePostValidator(new ValidatorIdentifiantChai());
    }

}
