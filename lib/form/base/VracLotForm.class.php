<?php
class VracLotForm extends acCouchdbObjectForm
{
	protected $configuration;
    
	public function __construct(ConfigurationVrac $configuration, acCouchdbJson $object, $options = array(), $CSRFSecret = null) 
	{
        $this->setConfiguration($configuration);
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function getConfiguration()
    {
    	return $this->configuration;
    }
    
    public function setConfiguration($configuration)
    {
    	$this->configuration = $configuration;
    }
    
	public function configure()
	{
		$this->setWidgets(array(
	       'numero' => new sfWidgetFormInputText(),
		   'assemblage' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(), 'expanded' => true)),
	       'degre' => new sfWidgetFormInputFloat(),
	       'presence_allergenes' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(), 'expanded' => true)),
		   'allergenes' => new sfWidgetFormInputText(),
	       'metayage' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(), 'expanded' => true)),
		   'bailleur' => new sfWidgetFormInputText(),
	       'montant' => new sfWidgetFormInputFloat()
		));
		$this->widgetSchema->setLabels(array(
	       'numero' => 'Numéro du lot:',
	       'assemblage' => 'Assemblage de millésimes:',
	       'degre' => 'Degrés:',
	       'presence_allergenes' => 'Allergènes:',
	       'allergenes' => '&nbsp;',
	       'metayage' => 'Métayage:',
		   'bailleur' => 'Nom du bailleur:',
	       'montant' => 'Montant:'
		));
		$this->setValidators(array(
	       'numero' => new sfValidatorString(array('required' => false)),
	       'assemblage' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
	       'degre' => new sfValidatorNumber(array('required' => false)),
	       'presence_allergenes' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
	       'allergenes' => new sfValidatorString(array('required' => false)),
	       'metayage' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
		   'bailleur' => new sfValidatorString(array('required' => false)),
	       'montant' => new sfValidatorNumber(array('required' => false)),
		));
        
        $millesimes = new VracLotMillesimeCollectionForm($this->getObject()->millesimes);
        $this->embedForm('millesimes', $millesimes);
        
        $cuves = new VracLotCuveCollectionForm($this->getObject()->cuves);
        $this->embedForm('cuves', $cuves);
        
		$this->widgetSchema->setNameFormat('lot[%s]');
	}
    
    public function getChoixOuiNon()
    {
    	return array('1' => 'Oui', '0' =>'Non'); 
    }

    public function getFormTemplateLotMillesimes() {
        $vrac = new Vrac();
        $lot = $vrac->lots->add();
        $form_embed = new VracLotMillesimeForm($this->getConfiguration(), $lot->millesimes->add());
        $form = new VracCollectionTemplateForm($this, 'millesimes', $form_embed);

        return $form->getFormTemplate();
    }

    public function getFormTemplateLotCuves() {
        $vrac = new Vrac();
        $lot = $vrac->lots->add();
        $form_embed = new VracLotCuveForm($this->getConfiguration(), $lot->cuves->add());
        $form = new VracCollectionTemplateForm($this, 'cuves', $form_embed);

        return $form->getFormTemplate();
    }
    
	public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
            if($form instanceof FormBindableInterface) {
                $form->bind($taintedValues[$key], $taintedFiles[$key]);
                $this->updateEmbedForm($key, $form);
            }
        }
    }
    
    public function updateEmbedForm($name, $form) {
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }
  
}