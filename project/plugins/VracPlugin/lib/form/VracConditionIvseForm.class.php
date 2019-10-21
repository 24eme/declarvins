<?php
class VracConditionIvseForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
		$this->setWidget('vin_livre', new sfWidgetFormInputHidden());
        unset($this['clause_reserve_retiraison'], $this['delai_paiement']);
        unset($this['has_transaction']);
        unset($this['annexe']);
    }

    public function conditionneIVSE() {
      return true;
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->has_transaction = 0;
    }
}