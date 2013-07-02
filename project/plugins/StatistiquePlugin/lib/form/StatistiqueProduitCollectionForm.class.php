<?php
class StatistiqueProduitCollectionForm extends BaseForm
{
	protected $interpro;
	
	public function __construct($interpro, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro = $interpro;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}
	
	public function configure() 
	{
		$this->embedForm (0, new StatistiqueProduitForm($this->interpro));
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
			$this->embedForm($key, new StatistiqueProduitForm($this->interpro));
		}
        parent::bind($taintedValues, $taintedFiles);
	}

	public function unEmbedForm($key)
	{
		unset($this->widgetSchema[$key]);
		unset($this->validatorSchema[$key]);
		unset($this->embeddedForms[$key]);
		if (isset($this->values[$key])) {
			unset($this->values[$key]);
		}
	}
    
}