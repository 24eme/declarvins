<?php
class ProduitLabelForm extends sfForm {

    public function configure() {
    	$this->setWidgets(array(
			'label' => new sfWidgetFormInputText(),
			'code' => new sfWidgetFormInputText()  		
    	));
		$this->widgetSchema->setLabels(array(
			'label' => 'Label*: ',
			'code' => 'Code*: '
		));
		$this->setValidators(array(
			'label' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'code' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
		));
        $this->widgetSchema->setNameFormat('produit_label[%s]');
    }
}