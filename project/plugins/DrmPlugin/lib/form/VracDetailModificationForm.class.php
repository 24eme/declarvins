<?php

class VracDetailModificationForm extends acCouchdbFormDocumentJson {

	public function configure() {
        $this->setWidgets(array(
        		'volume' => new sfWidgetFormInputFloat()
        ));
        $this->widgetSchema->setLabels(array(
        		'volume' => 'Volume '
        ));

        $this->setValidators(array(
        		'volume' => new sfValidatorNumber(array('required' => false))
        ));
		$this->widgetSchema->setNameFormat('vrac_'.$this->getObject()->getParent()->getParent()->getIdentifiant().'_'.$this->getObject()->getKey().'[%s]');
    }

}