<?php
class ConventionCielHabilitationForm extends acCouchdbObjectForm 
{
	
    public function configure() {
    	$teleprocedures = array('' => 'Non habilité', 'consulter' => 'Consulter', 'gérer' => 'Déclarer');
    	$telepaiements = array('' => 'Non habilité', 'adhérer' => 'Adhérer', 'télépayer' => 'Télépayer', 'adhérer_valider' => 'Adhérer et Télépayer');
        $this->setWidgets(array(
			'no_accises' => new sfWidgetFormInputText(),
			'identifiant' => new sfWidgetFormInputText(),
			'droit_teleprocedure' => new sfWidgetFormChoice(array('choices' => $teleprocedures)),
            'droit_telepaiement' => new sfWidgetFormChoice(array('choices' => $telepaiements)),
        ));
        $this->widgetSchema->setLabels(array(
			'no_accises' => 'Numéro accises: ',
			'identifiant' => 'Identifiant Prodou@ne*: ',
			'droit_teleprocedure' => 'Téléprocédure CIEL: ',
            'droit_telepaiement' => 'Télépaiement CIEL: ',
        ));
        $this->setValidators(array(
        		'no_accises' => new sfValidatorRegex(array('required' => false, 'pattern' => '/^FR[a-zA-Z0-9]{11}$/')),
    			'identifiant' => new sfValidatorString(array('required' => false)),
    			'droit_teleprocedure' => new sfValidatorChoice(array('choices' => array_keys($teleprocedures),'multiple' => false, 'required' => false)),
    			'droit_telepaiement' => new sfValidatorChoice(array('choices' => array_keys($telepaiements),'multiple' => false, 'required' => false)),
        ));
		$this->widgetSchema->setNameFormat('habilitation[%s]');
    }
	
}