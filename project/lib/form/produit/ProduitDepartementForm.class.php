<?php
class ProduitDepartementForm extends sfForm {

    public function configure() {
    	$this->setWidgets(array(
			'departement' => new sfWidgetFormInputText()	
    	));
		$this->widgetSchema->setLabels(array(
			'departement' => 'Département*: '
		));
		$this->setValidators(array(
			'departement' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
		));
        $this->widgetSchema->setNameFormat('produit_departement[%s]');
    }
}