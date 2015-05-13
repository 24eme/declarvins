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

      $this->setWidget('commentaires', new sfWidgetFormTextarea());
      $this->getWidget('commentaires')->setLabel("Commentaires");
      $this->setValidator('commentaires', new sfValidatorString(array('required' => false)));
      
      $this->embedForm('manquants', new DRMManquantsForm($this->getObject()->getOrAdd('manquants')));
	    
	    $this->widgetSchema->setNameFormat('drm_validation[%s]');
  	}
}