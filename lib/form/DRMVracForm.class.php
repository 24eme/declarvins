<?php
class DRMVracForm extends acCouchdbForm 
{

	protected $interpro;
	
  	public function __construct($doc, $interpro, $defaults = array(), $options = array(), $CSRFSecret = null) 
  	{
  		$this->interpro = $interpro;
  		
    	parent::__construct($doc, $defaults, $options, $CSRFSecret);
  	}
  	
	public function configure()
	{
		
		$details_vrac = $this->getDocument()->getDetailsAvecVrac();
        foreach ($details_vrac as $detail_vrac) {
        	$this->embedForm($detail_vrac->getHash(), new DRMVracContratsForm($detail_vrac, $this->interpro));
        }
        
  		$this->validatorSchema->setPostValidator(new DRMVracValidator());
		$this->widgetSchema->setNameFormat('drm_details_vrac[%s]');
    }
    
	public function bind(array $taintedValues = null, array $taintedFiles = null)
  	{
  		foreach ($this->embeddedForms as $key => $form) {
        	$form->bind($taintedValues[$key], $taintedFiles[$key]);
            $this->updateEmbedForm($key, $form);
        }
    	parent::bind($taintedValues, $taintedFiles);
  	}

    public function updateEmbedForm($name, $form) {
    	$this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public function getFormTemplateDetailContrats($key) 
    {
    	$detail = $this->getDocument()->get($key);
    	$object = $detail->vrac->add();
    	$choices = $this->getContratChoices($detail);
        $form_embed = new DRMVracContratForm($object, $choices);
        $form = new DRMVracCollectionTemplateForm($this, $key.'][contrats', $form_embed, 'var---nbItem---');

        return $form->getFormTemplate();
    }
    
    public function getContratChoices($object) 
    {
    	$prestation = false;
    	if ($this->interpro && $object->interpro != $this->interpro) {
    		$prestation = true;
    	}
	   $contrat_choices = $object->getContratsVracAutocomplete($prestation);
	   $contrat_choices[''] = '';
	   ksort($contrat_choices);
       return $contrat_choices;
    }
    
    public function update($values)
    {
    	foreach ($this->embeddedForms as $key => $form) {
    		$form->update($values[$key]);
    	}
    }
    
    public function save()
    {
    	$this->update($this->values);
    	$this->getDocument()->save();
    	return $this->getDocument();
    }
}