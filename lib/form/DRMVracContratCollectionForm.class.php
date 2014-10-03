<?php

class DRMVracContratCollectionForm extends acCouchdbObjectForm implements FormBindableInterface 
{
	public $virgin_object = null;
	protected $_contrat_choices;
	
	public function __construct(acCouchdbJson $object, $contratChoices, $options = array(), $CSRFSecret = null) {
		$this->_contrat_choices = $contratChoices;
		parent::__construct($object, $options, $CSRFSecret);
	}

	public function configure() 
	{
		if (count($this->getObject()) == 0) {
			$this->virgin_object = $this->getObject()->add();
		}
		foreach ($this->getObject() as $key => $object) {
			$this->embedForm ($key, new DRMVracContratForm($object, $this->_contrat_choices));
		}
    }
    
    
	
	public function bind(array $taintedValues = null, array $taintedFiles = null)
	{
		foreach ($this->embeddedForms as $key => $form) {
			if(!array_key_exists($key, $taintedValues)) {
				$this->unEmbedForm($key);
			}
		}
		foreach($taintedValues as $key => $values) {
			if(!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
				continue;
			}
			$this->embedForm($key, new DRMVracContratForm($this->getObject()->add(), $this->_contrat_choices));
		}
	}

	public function unEmbedForm($key)
	{
		unset($this->widgetSchema[$key]);
		unset($this->validatorSchema[$key]);
		unset($this->embeddedForms[$key]);
		$this->getObject()->remove($key);
	}
	
	public function offsetUnset($offset) 
	{
		parent::offsetUnset($offset);
		if (!is_null($this->virgin_object)) {
			$this->virgin_object->delete();
		}
    }
    
    public function update($values)
    {
    	foreach ($this->embeddedForms as $key => $form) {
    		$form->update($values[$key]);
    	}
    }
}