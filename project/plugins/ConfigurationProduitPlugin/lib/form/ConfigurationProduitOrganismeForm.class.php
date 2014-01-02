<?php
class ConfigurationProduitOrganismeForm extends sfForm 
{
    public function configure() 
    {
    	$choices = array_merge(array(''=>''), OIOCAllView::getInstance()->getAllOIOC());
    	$this->setWidgets(array(
			'date' => new sfWidgetFormInputText( array('default' => ''), array('class' => 'datepicker') ),
			'oioc' => new sfWidgetFormChoice(array('choices' => $choices))
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date: ',
			'oioc' => 'OI/OC: '
		)); 
		$this->setValidators(array(
			'date' => new sfValidatorString(array('required' => false)),
			'oioc' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices)))
		));
		if ($organisme = $this->getOption('organisme')) {
			$date = new DateTime($organisme->date);
			$this->setDefault('date', $date->format('d/m/Y'));
	    	$this->setDefault('oioc', $organisme->oioc);
		}		
        $this->widgetSchema->setNameFormat('produit_organisme[%s]');
    }
}