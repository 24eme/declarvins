<?php
class VracMarcheIrForm extends VracMarcheForm 
{	
    public function configure() {
        parent::configure();
		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
		$this->setValidator('poids', new sfValidatorNumber(array('required' => false)));
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && isset($this['type_transaction'])) {
            unset($this['poids']);
        }
        
        $this->validatorSchema->setPostValidator(new VracMarcheValidator());
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if ($this->getObject()->type_transaction == 'raisin' && !$values['poids']) {
    		$this->getObject()->poids = $this->getObject()->volume_propose;
    	}
    	$this->getObject()->has_cotisation_cvo = 1;
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('has_cotisation_cvo', 1);

    }
}