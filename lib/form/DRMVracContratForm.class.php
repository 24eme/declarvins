<?php

class DRMVracContratForm extends acCouchdbObjectForm
{
	protected $_contrat_choices;
	
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
        	'volume' => new sfWidgetFormInputFloat(),
        ));
        $this->widgetSchema->setLabels(array(
        	'vrac' => 'Contrat*: ',
        	'volume' => 'Volume*: ',
        ));
        $this->setValidators(array(
            'vrac' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContratChoices())), array('required' => 'Champ obligatoire')),
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
      if (is_null($this->_contrat_choices)) {
	   $this->_contrat_choices = array_merge(array('' => ''), $this->getObject()->getParent()->getParent()->getContratsVracAutocomplete());
      }
      return $this->_contrat_choices;
    }

}