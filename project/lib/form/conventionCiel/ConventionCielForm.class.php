<?php
class ConventionCielForm extends acCouchdbObjectForm {

    public function configure() {
    	$this->setWidgets(array(
			'raison_sociale' => new sfWidgetFormInputText(),
			'no_operateur' => new sfWidgetFormInputText(),
			'nom' => new sfWidgetFormInputText(),
			'prenom' => new sfWidgetFormInputText(),
			'fonction' => new sfWidgetFormInputText(),
			'telephone' => new sfWidgetFormInputText(),
            'email' => new sfWidgetFormInputText()
    					
    	));
    	$this->setValidators(array(
    			'raison_sociale' => new sfValidatorString(array('required' => true)),
    			'no_operateur' => new sfValidatorString(array('required' => true)),
    			'nom' => new sfValidatorString(array('required' => true)),
    			'prenom' => new sfValidatorString(array('required' => true)),
    			'fonction' => new sfValidatorString(array('required' => true)),
    			'telephone' => new sfValidatorString(array('required' => true)),
    			'fax' => new sfValidatorString(array('required' => false)),
    			'email' => new sfValidatorEmailStrict(array('required' => true))
    	));

    	if (!$this->getObject()->interpro) {
    		$choices = array('INTERPRO-IR' => 'InterRhône', 'INTERPRO-CIVP' => 'CIVP');
    		$this->setWidget('interpro', new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => true, 'multiple' => false, 'renderer_options' => array('formatter' => array($this, 'formatter')))));
    		$this->setValidator('interpro', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($choices),'multiple' => false)));
    	}
    	
		$this->widgetSchema->setLabels(array(
			'raison_sociale' => 'Raison Sociale*: ',
			'no_operateur' => 'N°SIREN/SIRET ou N°douane*: ',
			'nom' => 'Nom*: ',
			'prenom' => 'Prénom*: ',
			'fonction' => 'Fonction*: ',
			'telephone' => 'Téléphone*: ',
            'email' => 'Adresse e-mail*: ',
			'interpro' => 'Interprofession principale*:'
		));
		
		$formEtablissements = new ConventionCielEtablissementCollectionForm($this->getObject());
  		$this->embedForm('etablissements', $formEtablissements);

        $this->widgetSchema->setNameFormat('convention_ciel[%s]');
        

    }

    // on surcharge le template par defaut du widget
    public function formatter($widget, $inputs) {
        $rows = array();
        foreach ($inputs as $input) {
            $rows[] = $widget->renderContentTag('div', $input['input'] . $this->getOption('label_separator') . $input['label']);
        }

        return!$rows ? '' : implode($widget->getOption('separator'), $rows);
    }

}