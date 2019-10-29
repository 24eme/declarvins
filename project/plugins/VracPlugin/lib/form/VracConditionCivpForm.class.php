<?php
class VracConditionCivpForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['has_transaction'], $this['reference_contrat_pluriannuel']);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	$this->getObject()->has_transaction = 1;
        } else {
            $this->getObject()->has_transaction = 0;
        }
    }
}