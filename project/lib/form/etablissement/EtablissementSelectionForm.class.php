<?php
class EtablissementSelectionForm extends EtablissementChoiceForm {
	

	public function configure() {
    	parent::configure();
        $this->getWidget('identifiant')->setLabel('Etablissement :');
    }

    public function setName($name)
    {
    	$name = ($name)? $name : 'etablissement_selection';
    	$this->widgetSchema->setNameFormat($name.'[%s]');
    }

}