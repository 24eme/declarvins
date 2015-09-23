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
		   'bailleur' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
	       'numero' => 'Numéro du lot*:',
	       'assemblage' => 'Assemblage de millésimes:',
	       'degre' => 'Degré:',
	       'presence_allergenes' => 'Allergènes:',
	       'allergenes' => '&nbsp;',
	       'metayage' => 'Métayage:',
		   'bailleur' => 'Nom du bailleur et volumes:'
		));
		$this->setValidators(array(
	       'numero' => new sfValidatorString(array('required' => true)),
	       'assemblage' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))),
	       'degre' => new sfValidatorNumber(array('required' => false)),
	       'presence_allergenes' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
	       'allergenes' => new sfValidatorString(array('required' => false)),
	       'metayage' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
		   'bailleur' => new sfValidatorString(array('required' => false))
		));
        
        $millesimes = new VracLotMillesimeCollectionForm($this->getObject()->millesimes);
        $this->embedForm('millesimes', $millesimes);
        
        $cuves = new VracLotCuveCollectionForm($this->getObject()->cuves);
        $this->embedForm('cuves', $cuves);
        
  		$this->validatorSchema->setPostValidator(new VracLotValidator());
        
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
        parent::bind($taintedValues, $taintedFiles);
    }
    
    public function updateEmbedForm($name, $form) {
    	$this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      if (is_null($this->getObject()->presence_allergenes)) {
        $this->setDefault('presence_allergenes', 0);
        $this->getObject()->set('presence_allergenes', 0);
      }  
    }

    protected function doUpdateObject($values) {
      parent::doUpdateObject($values); 
      if (!$this->getObject()->metayage) {
          $this->getObject()->bailleur = '';
      }
      if (!$this->getObject()->assemblage) {
        $values['millesimes'] = array();
        $this->getObject()->remove('millesimes');
        $this->getObject()->add('millesimes');
      }
    }
  
}