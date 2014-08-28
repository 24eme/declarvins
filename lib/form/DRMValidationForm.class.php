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

      $this->setWidget('commentaire', new sfWidgetFormTextarea());
      $this->getWidget('commentaire')->setLabel("Commentaires");
      $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
	    
	    $this->widgetSchema->setNameFormat('drm_validation[%s]');
  	}
}