<?php
class ConventionCielForm extends acCouchdbObjectForm {
	
	protected static $_francize_date = array(
			'date_fin_exercice',
			'date_ciel'
	);

    public function configure() {
    	$this->setWidgets(array(
			'raison_sociale' => new sfWidgetFormInputText(),
			'no_operateur' => new sfWidgetFormInputText(),
			'no_siret_payeur' => new sfWidgetFormInputText(),
			'nom' => new sfWidgetFormInputText(),
			'prenom' => new sfWidgetFormInputText(),
			'telephone' => new sfWidgetFormInputText(),
            'email' => new sfWidgetFormInputText(),
    		'adresse' => new sfWidgetFormInputText(),
    		'code_postal' => new sfWidgetFormInputText(),
    		'commune' => new sfWidgetFormInputText(),
	        'telephone_beneficiaire' => new sfWidgetFormInputText(),
	        'email_beneficiaire' => new sfWidgetFormInputText(),
	        'date_fin_exercice' => new sfWidgetFormInputText(),
	        'date_ciel' => new sfWidgetFormInputText(),
    		'representant_legal' => new sfWidgetFormChoice(array('choices' => array(1 => "Oui", 0 => "Non"), 'multiple' => false, 'expanded' => true, 'renderer_options' => array('formatter' => array($this, 'formatter')))),
    		'mandataire' => new sfWidgetFormChoice(array('choices' => array(null => null, 'teledeclaration' => 'Télédéclaration', 'telepaiement' => 'Télépaiement'))),
    					
    	));
    	$this->setValidators(array(
    			'raison_sociale' => new sfValidatorString(array('required' => true)),
    			'no_operateur' => new sfValidatorString(array('required' => true)),
    			'no_siret_payeur' => new ValidatorSiret(array('required' => true)),
    			'nom' => new sfValidatorString(array('required' => true)),
    			'prenom' => new sfValidatorString(array('required' => true)),
    			'telephone' => new sfValidatorString(array('required' => true)),
    			'email' => new sfValidatorEmailStrict(array('required' => true)),
    			'adresse' => new sfValidatorString(array('required' => true)),
    			'code_postal' => new sfValidatorString(array('required' => true)),
    			'commune' => new sfValidatorString(array('required' => true)),
	       		'telephone_beneficiaire' => new sfValidatorString(array('required' => true)),
	       		'email_beneficiaire' => new sfValidatorEmailStrict(array('required' => true)),
	       		'date_fin_exercice' =>new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)),
	       		'date_ciel' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)),
    			'representant_legal' => new ValidatorBoolean(array('required' => true)),
    			'mandataire' => new sfValidatorChoice(array('required' => false, 'choices' => array(null, 'teledeclaration', 'telepaiement'))),
    	));

    	if (!$this->getObject()->interpro) {
    		$choices = array('INTERPRO-IR' => 'InterRhône', 'INTERPRO-CIVP' => 'CIVP');
    		$this->setWidget('interpro', new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => true, 'multiple' => false, 'renderer_options' => array('formatter' => array($this, 'formatter')))));
    		$this->setValidator('interpro', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($choices),'multiple' => false)));
    	}
    	
		$this->widgetSchema->setLabels(array(
			'raison_sociale' => 'Raison Sociale*: ',
			'no_operateur' => 'N° SIREN/SIRET ou N° douane*: ',
			'no_siret_payeur' => 'N° du SIRET payeur*: ',
			'nom' => 'Nom*: ',
			'prenom' => 'Prénom*: ',
			'telephone' => 'Téléphone*: ',
            'email' => 'Courriel*: ',
			'interpro' => 'Interprofession principale*:',
	        'adresse' => 'Adresse*: ',
	        'code_postal' => 'Code postal*: ',
	        'commune' => 'Commune*: ',
	        'telephone_beneficiaire' => 'Téléphone*: ',
	        'email_beneficiaire' => 'Courriel*: ',
	        'date_fin_exercice' => 'Date de fin de l\'exercice*: ',
	        'date_ciel' => 'Date de passage envisagé à CIEL*: ',
			'representant_legal' => 'Agissant en qualité de représentant légal:',
			'mandataire' => 'Mandataire:'
		));
		
		$formEtablissements = new ConventionCielEtablissementCollectionForm($this->getObject());
  		$this->embedForm('etablissements', $formEtablissements);
  		$this->mergePostValidator(new ValidatorConventionCiel());
        $this->widgetSchema->setNameFormat('convention_ciel[%s]');
    }
    


    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (isset($values['date_fin_exercice']) && $values['date_fin_exercice']) {
    		$date = new DateTime($values['date_fin_exercice']);
    		$this->getObject()->date_fin_exercice = $date->format('Y-m-d');
    	}
    	if (isset($values['date_ciel']) && $values['date_ciel']) {
    		$date = new DateTime($values['date_ciel']);
    		$this->getObject()->date_ciel = $date->format('Y-m-d');
    	}
    }
    
    protected function updateDefaultsFromObject() {
    	parent::updateDefaultsFromObject();
    	$defaults = $this->getDefaults();
    	foreach (self::$_francize_date as $field) {
    		if (isset($defaults[$field]) && !empty($defaults[$field])) {
    			$date = new DateTime($defaults[$field]);
    			$defaults[$field] = $date->format('d/m/Y');
    		}
    	}
    	$this->setDefaults($defaults);
    }

    // on surcharge le template par defaut du widget
    public function formatter($widget, $inputs) {
        $rows = array();
        foreach ($inputs as $input) {
            $rows[] = $widget->renderContentTag('span', $input['input'] . $this->getOption('label_separator') . $input['label']);
        }

        return!$rows ? '' : implode($widget->getOption('separator'), $rows);
    }

}