<?php
class ConfigurationProduitDroitForm extends sfForm 
{
    public function configure() 
    {
    	
		$type = $this->getOption('type');
    	$this->setWidgets(array(
			'date' => new sfWidgetFormInputText( array('default' => ''), array('class' => 'datepicker') ),
			'code' => new sfWidgetFormInputText(),
			'libelle' => new sfWidgetFormInputText(),
			'taux' => new sfWidgetFormInputFloat()  		
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date '.$type.': ',
			'code' => 'Code '.$type.': ',
			'libelle' => 'Libelle '.$type.': ',
			'taux' => 'Taux '.$type.': '
		)); 
		$this->setValidators(array(
			'date' => new sfValidatorString(array('required' => false)),
			'code' => new sfValidatorString(array('required' => false)),
			'libelle' => new sfValidatorString(array('required' => false)),
			'taux' => new sfValidatorNumber(array('required' => false))
		));
		if ($droit = $this->getOption('droit')) {
			$date = new DateTime($droit->date);
			$this->setDefault('date', $date->format('d/m/Y'));
	    	$this->setDefault('code',$droit->code);
	    	$this->setDefault('libelle', $droit->libelle);
	    	$this->setDefault('taux', $droit->taux);
		}		
        $this->widgetSchema->setNameFormat('produit_droit[%s]');
    }
}