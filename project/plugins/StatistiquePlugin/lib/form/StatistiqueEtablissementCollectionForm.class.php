<?php
class StatistiqueEtablissementCollectionForm extends BaseForm
{
	protected $interpro;
	protected $etablissement_options;
	
	public function __construct($interpro, $etablissementOptions = array(), $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro = $interpro;
  		$this->etablissement_options = $etablissementOptions;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}
  	
	public function configure() 
	{
		$this->embedForm (0, new StatistiqueEtablissementForm($this->interpro, $this->etablissement_options));
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
			$this->embedForm($key, new StatistiqueEtablissementForm($this->interpro, $this->etablissement_options));
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