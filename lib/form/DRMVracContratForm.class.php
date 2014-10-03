<?php

class DRMVracContratForm extends acCouchdbObjectForm
{
	protected $_contrat_choices;
	
	public function __construct(acCouchdbJson $object, $contratChoices, $options = array(), $CSRFSecret = null) {
		$this->_contrat_choices = $contratChoices;
		parent::__construct($object, $options, $CSRFSecret);
	}
	
	protected function updateDefaultsFromObject() {
		parent::updateDefaultsFromObject();
        
		if ($key = $this->getObject()->getKey()) {
        	$this->setDefault('vrac', $key);
        }
    }

	public function configure() 
	{
        $this->setWidgets(array(
            'vrac' => new sfWidgetFormChoice(array('choices' => $this->getContratChoices())),
        	'volume' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
        ));
        $this->widgetSchema->setLabels(array(
        	'vrac' => 'Contrat*: ',
        	'volume' => 'Volume*: ',
        ));
        $this->setValidators(array(
            'vrac' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContratChoices()))),
        	'volume' => new sfValidatorNumber(array('required' => true)),
        ));


        $this->widgetSchema->setNameFormat('contrat[%s]');
    }
    
    public function update($values)
    {	
    	$contrat = $this->getObject()->getParent()->getOrAdd($values['vrac']);
    	$contrat->volume = $values['volume'];
    }
    
    public function getContratChoices() 
    {
      return $this->_contrat_choices;
    }

}