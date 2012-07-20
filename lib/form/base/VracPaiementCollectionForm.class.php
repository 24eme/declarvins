<?php
class VracPaiementCollectionForm extends acCouchdbObjectForm implements FormBindableInterface
{
	
	protected $virgin_object = null;
	
	public function configure()
	{
		if (count($this->getObject()) == 0) {
			$this->virgin_object = $this->getObject()->add();
		}
		
		foreach ($this->getObject() as $key => $object) {
			$this->embedForm ($key, new VracPaiementForm($object));
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

			$this->embedForm($key, new VracPaiementForm($this->getObject()->add()));
		}
		parent::bind($taintedValues, $taintedFiles);
	}

	public function unEmbedForm($key)
	{
		unset($this->widgetSchema[$key]);
		unset($this->validatorSchema[$key]);
		unset($this->embeddedForms[$key]);
		$this->getObject()->remove($key);
	}
	
	
	public function offsetUnset($offset) {
		parent::offsetUnset($offset);
		if (!is_null($this->virgin_object)) {
			$this->virgin_object->delete();
		}
    }
}