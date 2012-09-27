<?php
class VracPaiementCollectionForm extends acCouchdbObjectForm implements FormBindableInterface
{
	public $virgin_object = null;
	protected $embed_form_name;
	
	public function __construct($embedFormName, acCouchdbJson $object, $options = array(), $CSRFSecret = null) 
	{
        $this->setEmbedFormName($embedFormName);
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function getEmbedFormName()
    {
    	return $this->embed_form_name;
    }
    
    public function setEmbedFormName($embedFormName)
    {
    	$this->embed_form_name = $embedFormName;
    }
	
	public function configure()
	{
		if (count($this->getObject()) == 0) {
			$this->virgin_object = $this->getObject()->add();
		}
		$embedFormName = $this->getEmbedFormName();
		foreach ($this->getObject() as $key => $object) {
			$this->embedForm ($key, new $embedFormName($object));
		}
	}
	
	public function bind(array $taintedValues = null, array $taintedFiles = null)
	{
		foreach ($this->embeddedForms as $key => $form) {
			if(!array_key_exists($key, $taintedValues)) {
				$this->unEmbedForm($key);
			}
		}
		$embedFormName = $this->getEmbedFormName();
		foreach($taintedValues as $key => $values) {
			if(!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
				continue;
			}
			$this->embedForm($key, new $embedFormName($this->getObject()->add()));
		}
        //parent::bind($taintedValues, $taintedFiles);
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