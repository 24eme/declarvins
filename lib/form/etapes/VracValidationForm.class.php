<?php
class VracValidationForm extends VracForm 
{
	protected static $_francize_date = array(
    	'date_signature',
    );
	public function configure()
    {
    	parent::configure();    	
		$this->setWidget('email', new sfWidgetFormInputHidden());
		$this->setValidator('email', new ValidatorPass());
		if ($this->user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
			$fields = array('valide','commentaires', 'date_signature', 'email');
		} else {
			$fields = array('valide','commentaires', 'email');
		}
		$this->useFields($fields);
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
        $this->getObject()->getDocument()->validate($this->user);
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
        $this->setDefaults($defaults);     
    }
}