<?php
class VracAnnulationForm extends acCouchdbObjectForm
{
	protected $date_validation_field;
	protected $user;
	protected $etablissement;
	
	public function __construct(acCouchdbJson $object, $acteur = null, $user = null, $etablissement = null, $options = array(), $CSRFSecret = null) 
	{
		$acteurs = VracClient::getInstance()->getActeurs();
      	if ($acteur && in_array($acteur, $acteurs)) {
        	$this->date_annulation_field = 'date_annulation_'.$acteur;
      	} else {
      		throw new sfException('Acteur '.$acteur.' invalide!');
      	}
      	$this->user = $user;
      	$this->etablissement = $etablissement;
        parent::__construct($object, $options, $CSRFSecret);
    }
	public function configure()
	{
		$this->setWidgets(array(
	       'date_annulation_vendeur' => new sfWidgetFormInputHidden(),
	       'date_annulation_acheteur' => new sfWidgetFormInputHidden(),
	       'date_annulation_mandataire' => new sfWidgetFormInputHidden()
		));
		$this->setValidators(array(
	       'date_annulation_vendeur' => new ValidatorPass(),
	       'date_annulation_acheteur' => new ValidatorPass(),
	       'date_annulation_mandataire' => new ValidatorPass()
		));
		$this->widgetSchema->setNameFormat('valide[%s]');
	}

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      if (!($this->getObject()->get($this->date_annulation_field))) {
        $this->setDefault($this->date_annulation_field, date('c'));
      }  
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
        $this->getObject()->getDocument()->annuler($this->user, $this->etablissement);
    }
}