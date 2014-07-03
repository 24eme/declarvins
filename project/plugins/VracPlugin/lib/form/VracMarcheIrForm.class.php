<?php
class VracMarcheIrForm extends VracMarcheForm 
{
    public function configure() {
        parent::configure();
		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
        unset($this['annexe']);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && isset($this['type_transaction'])) {
            unset($this['type_transaction']);
        }
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		$this->getObject()->type_transaction = VracClient::TRANSACTION_DEFAUT;
    	}
    }
}