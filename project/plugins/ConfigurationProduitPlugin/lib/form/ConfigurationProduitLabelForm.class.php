<?php
class ConfigurationProduitLabelForm extends sfForm 
{

    public function configure() {    	
    	$this->setWidgets(array(
    		'label' => new sfWidgetFormInputText(),
    		'code' => new sfWidgetFormInputText()
    	));
    	$this->setValidators(array(
    		'label' => new sfValidatorString(array('required' => false)),
    		'code' => new sfValidatorString(array('required' => false))
    	));
		$this->widgetSchema->setLabels(array(
			'label' => 'Libellé label: ',
			'code' => 'Code label: '
		));
    	$this->setDefault('label', $this->getOption('libelle_label'));
    	$this->setDefault('code', $this->getOption('code_label'));
    	$this->widgetSchema->setNameFormat('produit_label[%s]');
    }
}