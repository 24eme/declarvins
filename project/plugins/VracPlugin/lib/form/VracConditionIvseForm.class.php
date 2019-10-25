<?php
class VracConditionIvseForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['has_transaction'], $this['annexe'], $this['bailleur_metayer']);
    }

    public function conditionneIVSE() {
      return true;
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->has_transaction = 0;
    }
}