<?php
class ConventionCielHabilitationForm extends acCouchdbObjectForm 
{
	
    public function configure() {
    	$teleprocedures = array('' => 'Non habilité', 'consulter' => 'Habilité à consulter', 'gérer' => 'Habilité à gérer');
    	$telepaiements = array('' => 'Non habilité', 'adhérer' => 'Habilité à adhérer', 'valider' => 'Habilité à valider');
        $this->setWidgets(array(
			'no_accises' => new sfWidgetFormInputText(),
			'nom' => new sfWidgetFormInputText(),
			'prenom' => new sfWidgetFormInputText(),
			'identifiant' => new sfWidgetFormInputText(),
			'droit_teleprocedure' => new sfWidgetFormChoice(array('choices' => $teleprocedures)),
            'droit_telepaiement' => new sfWidgetFormChoice(array('choices' => $telepaiements)),
            'mensualisation' => new WidgetFormInputCheckbox()
        ));
        $this->widgetSchema->setLabels(array(
			'no_accises' => 'Numéro accises: ',
			'nom' => 'Nom*: ',
			'prenom' => 'Prénom*: ',
			'identifiant' => 'Identifiant Prodou@ne*: ',
			'droit_teleprocedure' => 'Habilitation téléprocédure CIEL: ',
            'droit_telepaiement' => 'Habilitation télépaiement CIEL: ',
			'mensualisation' => 'Echéance mensuelle:'
        ));
        $this->setValidators(array(
        		'no_accises' => new sfValidatorString(array('required' => false, 'max_length' => 13, 'min_length' => 13)),
    			'nom' => new sfValidatorString(array('required' => true)),
    			'prenom' => new sfValidatorString(array('required' => true)),
    			'identifiant' => new sfValidatorString(array('required' => true)),
    			'droit_teleprocedure' => new sfValidatorChoice(array('choices' => array_keys($teleprocedures),'multiple' => false, 'required' => false)),
    			'droit_telepaiement' => new sfValidatorChoice(array('choices' => array_keys($telepaiements),'multiple' => false, 'required' => false)),
    			'mensualisation' => new ValidatorBoolean(array('required' => false))
        ));
		$this->widgetSchema->setNameFormat('habilitation[%s]');
    }
	
}