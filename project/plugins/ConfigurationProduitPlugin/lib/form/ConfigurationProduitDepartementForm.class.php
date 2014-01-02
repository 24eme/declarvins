<?php
class ConfigurationProduitDepartementForm extends sfForm 
{
    public function configure() 
    {
    	$this->setWidgets(array(
    		'departement' => new sfWidgetFormInputText()
    	));
    	$this->setValidators(array(
    		'departement' => new sfValidatorString(array('required' => false))
    	));
		$this->widgetSchema->setLabels(array(
			'departement' => 'Département: '
		));
    	$this->setDefault('departement', $this->getOption('departement'));
        $this->widgetSchema->setNameFormat('produit_departement[%s]');
    }
}