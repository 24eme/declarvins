<?php
class DRMValidationForm extends BaseForm
{
	public function configure()
  	{
  		if (!($engagements = $this->getOption('engagements'))) {
  			throw new sfException('Engagements are needed');
  		}
  		
  		foreach ($engagements as $engagement) {
  			$this->setWidget('engagement_'.$engagement->getCode(), new sfWidgetFormInputCheckbox());
  			$this->getWidget('engagement_'.$engagement->getCode())->setLabel($engagement->getMessage());
  			$this->setValidator('engagement_'.$engagement->getCode(), new sfValidatorBoolean(array('required' => true)));
  		}
	    
	    $this->widgetSchema->setNameFormat('drm_validation[%s]');
  	}
}