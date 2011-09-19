<?php

class TiersForm extends sfCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
                                'identifiant' => new sfWidgetFormInputText(array('label' => 'Identifiant')),
				'nom' => new sfWidgetFormInputText(array('label' => 'Nom')),
				));
	
        $this->setValidators(array(
                                    'identifiant' => new sfValidatorString(array('required' => true), array('required' => 'Champ Requis')),
                                    'nom' => new sfValidatorString(array('required' => true), array('required' => 'Champ Requis')),
                                    ));

        $this->widgetSchema->setNameFormat('tiers[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!$this->getObject()->get('_id'))
        {
            $this->getObject()->set('_id', 'CHAI-'.$this->getObject()->getIdentifiant());
        }
    }

}
