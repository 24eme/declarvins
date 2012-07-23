<?php
class VracLotCollectionForm extends acCouchdbObjectForm implements FormBindableInterface
{
	public $virgin_object = null;
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
		if (count($this->getObject()) == 0) {
			$this->virgin_object = $this->getObject()->add();
		}
		foreach ($this->getObject() as $key => $object) {
			$this->embedForm ($key, new VracLotForm($this->getConfiguration(), $object));
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
			$this->embedForm($key, new VracLotForm($this->getObject()->add()));
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