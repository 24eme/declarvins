<?php
class DRMValidationForm extends acCouchdbObjectForm
{
	public function configure()
  	{

  		$engagements = $this->getOption('engagements');
  		foreach ($engagements as $engagement) {
  			$this->setWidget('engagement_'.$engagement->getCode(), new sfWidgetFormInputCheckbox());
  			$this->getWidget('engagement_'.$engagement->getCode())->setLabel($engagement->getMessage());
  			$this->setValidator('engagement_'.$engagement->getCode(), new sfValidatorBoolean(array('required' => true)));
  		}
	  $this->setWidget('brouillon', new sfWidgetFormInputHidden());
      $this->setValidator('brouillon', new ValidatorPass());

      if($this->getOption('is_admin')) {
        $this->setWidget('commentaires', new sfWidgetFormTextarea());
        $this->getWidget('commentaires')->setLabel("Commentaires BO");
        $this->setValidator('commentaires', new sfValidatorString(array('required' => ($this->getObject()->hasVersion())? true : false)));
      }

      $this->embedForm('manquants', new DRMManquantsForm($this->getObject()->getOrAdd('manquants')));
	    
	    $this->widgetSchema->setNameFormat('drm_validation[%s]');
  	}
    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        $defaults['brouillon'] = 0;
        $this->setDefaults($defaults);     
    }

    protected function doSave($con = null) 
    {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();
    }

    protected function doUpdateObject($values) 
    {
        $this->getObject()->fromArray($values);
        if ($this->getObject()->type == DRMFictive::TYPE) {
        	$drm = $this->getObject()->getDRM();
        	$drm->fromArray($values);
        	$this->getObject()->setDRM($drm);
        }
        if (isset($values['observationsProduits'])) {
	        foreach ($values['observationsProduits'] as $key => $value) {
	        	if ($value['observations']) {
	        		$this->getObject()->addObservationProduit($key, $value['observations']);
	        	}
	        }
        }
    }
}