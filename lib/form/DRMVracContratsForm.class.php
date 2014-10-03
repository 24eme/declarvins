<?php
class DRMVracContratsForm extends acCouchdbObjectForm 
{
	protected $_contrat_choices;
	
	public function configure()
	{
		$choices = $this->getContratChoices();
        $contrats = new DRMVracContratCollectionForm($this->getObject()->vrac, $choices);
        $this->embedForm('contrats', $contrats);
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
    
    public function getContratChoices() 
    {
      if (is_null($this->_contrat_choices)) {
	   $this->_contrat_choices = $this->getObject()->getContratsVracAutocomplete();
	   $this->_contrat_choices[''] = '';
	   ksort($this->_contrat_choices);
      }
      return $this->_contrat_choices;
    }

    public function updateEmbedForm($name, $form) {
    	$this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }
    
    public function update($values)
    {
    	foreach ($this->embeddedForms as $key => $form) {
    		$form->update($values[$key]);
    	}
    }

}