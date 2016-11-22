<?php

class DRMCrdsForm extends acCouchdbObjectForm 
{

    public function configure() 
    {

    	$crds = new DRMCrdCollectionForm($this->getObject()->crds);
    	$this->embedForm('crds', $crds);
        $this->widgetSchema->setNameFormat('drm[%s]');
    }

    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	foreach ($this->embeddedForms as $key => $form) {
    		$form->doUpdateObject($values[$key]);
    	}
    }

    
}