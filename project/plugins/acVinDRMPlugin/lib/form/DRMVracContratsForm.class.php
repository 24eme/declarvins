<?php
class DRMVracContratsForm extends acCouchdbObjectForm
{

	protected $interpro;
	
  	public function __construct($doc, $interpro, $options = array(), $CSRFSecret = null) 
  	{
  		$this->interpro = $interpro;
    	parent::__construct($doc, $options, $CSRFSecret);
  	}

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
    	$prestation = false;
    	$withSolde = $this->getObject()->getDocument()->hasVersion();
    	if ($this->interpro && $this->getObject()->interpro != $this->interpro) {
    		$prestation = true;
    	}
           $contrat_choices = $this->getObject()->getContratsVracAutocomplete($prestation, $withSolde);
           $contrat_choices[''] = '';
           ksort($contrat_choices);
        return $contrat_choices;
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public function update($values)
    {
    	$this->getObject()->remove('vrac');
    	$this->getObject()->add('vrac');
        foreach ($values['contrats'] as $numero => $values) {
        	$contrat = $this->getObject()->get('vrac')->getOrAdd(trim($values['vrac']));
        	$contrat->volume = $values['volume'];
        }
    }

}