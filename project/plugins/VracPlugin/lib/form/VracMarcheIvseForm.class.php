<?php
class VracMarcheIvseForm extends VracMarcheForm 
{
    public function configure() {
        parent::configure();
		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
		$this->getWidget('millesime')->setLabel('Année de production*:');
		$this->getValidator('millesime')->setOption('required', true);
		unset($this['non_millesime']);
        unset($this['annexe']);
        unset($this['has_transaction']);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && isset($this['type_transaction'])) {
            unset($this['type_transaction']);
        }
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		$this->getObject()->type_transaction = VracClient::TRANSACTION_DEFAUT;
    	}
    	$this->getObject()->has_transaction = 0;
    }
}