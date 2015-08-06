<?php

class DRMCrdCollectionForm extends acCouchdbObjectForm
{
	public function configure()
	{
    	foreach ($this->getObject() as $key => $object) {
        	$this->embedForm ($key, new DRMCrdForm($object));
        }
    }

    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	foreach ($this->embeddedForms as $key => $form) {
    		$form->doUpdateObject($values[$key]);
    	}
    }
}
