<?php
class VracValidationForm extends VracForm
{
	protected static $_francize_date = array(
    	'date_signature',
		'date_stats'
    );
	public function configure()
    {
		$this->setWidget('email', new sfWidgetFormInputHidden());
		$this->setValidator('email', new ValidatorPass());
		$this->setWidget('brouillon', new sfWidgetFormInputHidden());
		$this->setValidator('brouillon', new ValidatorPass());
		$this->setWidget('commentaires', new sfWidgetFormTextarea());
		$this->setValidator('commentaires', new sfValidatorString(array('required' => false)));
		$this->setWidget('observations', new sfWidgetFormTextarea());
        if ($this->getObject()->hasVersion()&&!$this->user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $this->setValidator('observations', new sfValidatorString(array('required' => true), array('required' => 'Vous devez préciser les raisons des modifications apportées aux contrats')));
        } else {
            $this->setValidator('observations', new sfValidatorString(array('required' => false)));
        }
		if ($this->user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
			$this->setWidget('date_signature', new sfWidgetFormInputText());
			$this->setValidator('date_signature', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)));
			$this->setWidget('date_stats', new sfWidgetFormInputText());
			$this->setValidator('date_stats', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)));
		}
		if (!$this->user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $this->getObject()->hasOioc()) {
			$this->setWidget('transaction', new sfWidgetFormInputCheckbox());
			$this->setValidator('transaction', new sfValidatorBoolean(array('required' => true)));
			$this->getWidget('transaction')->setLabel('J\'ai pris connaissance de l\'envoi automatique de ma déclaration de transaction.');
		}
        $this->widgetSchema->setLabels(array(
        	'date_signature' => 'Date de signature*:',
        	'date_stats' => 'Date de statistique:',
        	'commentaires' => 'Commentaires BO:',
        	'observations' => 'Observations:'
        ));
		$vracValideFormName = $this->vracValideFormName();
        $valide = new VracValideForm($this->getObject()->valide);
        $this->embedForm('valide', $valide);

        $this->widgetSchema->setNameFormat('vrac_validation[%s]');
    }

    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (isset($values['date_signature']) && $values['date_signature']) {
    		$date = new DateTime($values['date_signature']);
    		$this->getObject()->getDocument()->date_signature = $date->format('c');
    	}
    	if (isset($values['valide']['date_saisie']) && $values['valide']['date_saisie']) {
    		$date = new DateTime($values['valide']['date_saisie']);
    		$this->getObject()->getDocument()->valide->date_saisie = $date->format('c');
    	}
    	if (isset($values['date_stats']) && $values['date_stats']) {
    		$date = new DateTime($values['date_stats']);
    		$this->getObject()->getDocument()->date_stats = $date->format('c');
    	}
    	if (isset($values['date_stats']) && !$values['date_stats']) {
    		$this->getObject()->getDocument()->date_stats = $this->getObject()->getDocument()->valide->date_saisie;
    	}
    	if (isset($values['brouillon']) && !$values['brouillon']) {
        	$this->getObject()->getDocument()->validate($this->user, $this->etablissement);
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
        $defaults['email'] = 1;
        $defaults['brouillon'] = 0;
        $this->setDefaults($defaults);
    }

    public function hasClauses() {
        return true;
    }
}
