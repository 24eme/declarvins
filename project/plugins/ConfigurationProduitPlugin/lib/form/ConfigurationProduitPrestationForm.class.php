<?php
class ConfigurationProduitPrestationForm extends sfForm 
{
    public function configure() 
    {
    	$obj = $this->getOption('object');
    	$choices = $this->getChoices($obj->getDocument()->interpro);
    	$this->setWidgets(array(
    		'prestation' => new sfWidgetFormChoice(array('choices' => $choices, 'multiple' => true, 'expanded' => true, 'renderer_options' => array('class' => '')))
    	));
    	$this->setValidators(array(
    		'prestation' => new sfValidatorChoice(array('choices' => array_keys($choices), 'multiple' => true, 'required' => false))
    	));
		$this->widgetSchema->setLabels(array(
			'prestation' => 'Prestation: '
		));
    	$this->setDefault('prestation', $obj->getOrAdd('prestations')->toArray());
        $this->widgetSchema->setNameFormat('produit_prestation[%s]');
    }
    
    protected function getChoices($except = null)
    {
    	$choices = array();
    	$interpros = InterproClient::getInstance()->getAllInterpros();
    	foreach ($interpros as $interpro) {
    		if ($except && $except == $interpro) {
    			continue;
    		}
    		$obj = InterproClient::getInstance()->find($interpro);
    		$choices[$interpro] = $obj->nom;
    	}
    	return $choices;
    }
}