<?php
class VracConditionCivpForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['has_transaction']);
		$this->getWidget('conditions_paiement')->setLabel('Conditions de vente:');
		$this->getValidator('conditions_paiement')->setOption('required', false);
		$this->getWidget('reference_contrat_pluriannuel')->setLabel('Si vous avez un contrat pluriannuel écrit, veuillez noter sa référence:');
    }


    public function conditionneReferenceContrat() {
      return false;
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	$this->getObject()->has_transaction = 1;
        }
    }
}