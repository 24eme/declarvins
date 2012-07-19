<?php
class VracPaiementCollectionForm extends acCouchdbObjectForm implements FormBindableInterface
{
	public function configure()
	{
		$key = 0;
		foreach ($this->getObject() as $key => $object) {
			$this->embedForm ($key, new VracPaiementForm($object));
		}
		$this->embedForm (($key+1), new VracPaiementForm($this->getObject()->add()));
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
			print_r($this->getObject()->toArray());exit;
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
}