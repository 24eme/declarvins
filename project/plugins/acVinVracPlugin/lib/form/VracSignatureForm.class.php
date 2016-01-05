<?php
class VracSignatureForm extends acCouchdbObjectForm
{
	protected $date_validation_field;
	
	public function __construct(acCouchdbJson $object, $acteur = null, $options = array(), $CSRFSecret = null) 
	{
		$acteurs = VracClient::getInstance()->getActeurs();
      	if ($acteur && in_array($acteur, $acteurs)) {
        	$this->date_validation_field = 'date_validation_'.$acteur;
      	} else {
      		throw new sfException('Acteur '.$acteur.' invalide!');
      	}
        parent::__construct($object, $options, $CSRFSecret);
    }
	public function configure()
	{
		$this->setWidgets(array(
	       'date_validation_vendeur' => new sfWidgetFormInputHidden(),
	       'date_validation_acheteur' => new sfWidgetFormInputHidden(),
	       'date_validation_mandataire' => new sfWidgetFormInputHidden()
		));
		$this->setValidators(array(
	       'date_validation_vendeur' => new ValidatorPass(),
	       'date_validation_acheteur' => new ValidatorPass(),
	       'date_validation_mandataire' => new ValidatorPass()
		));
		
		if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $this->getObject()->hasOioc()) {
			$this->setWidget('transaction', new sfWidgetFormInputCheckbox());
			$this->setValidator('transaction', new sfValidatorBoolean(array('required' => true)));
			$this->getWidget('transaction')->setLabel('J\'ai pris connaissance de l\'envoi automatique de ma déclaration de transaction.');
		}
		$this->widgetSchema->setNameFormat('valide[%s]');
	}

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      if (!($this->getObject()->get($this->date_validation_field))) {
        $this->setDefault($this->date_validation_field, date('c'));
      }  
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
        $this->getObject()->getDocument()->updateStatut();
    }
}