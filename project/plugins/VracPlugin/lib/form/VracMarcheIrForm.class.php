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

        $this->setWidget('mercuriale_mois', new sfWidgetFormInputText());
        $this->setValidator('mercuriale_mois', new sfValidatorInteger(array('required' => false, 'min' => 1, 'max' => 12)));
        $this->setWidget('mercuriale_annee', new sfWidgetFormInputText());
        $this->setValidator('mercuriale_annee', new sfValidatorInteger(array('required' => false, 'min' => 2000, 'max' => 2100)));
        $this->setWidget('variation_hausse', new sfWidgetFormInputFloat());
        $this->setValidator('variation_hausse', new sfValidatorNumber(array('required' => false)));
        $this->setWidget('variation_baisse', new sfWidgetFormInputFloat());
        $this->setValidator('variation_baisse', new sfValidatorNumber(array('required' => false)));
        
        $this->validatorSchema->setPostValidator(new VracMarcheValidator());
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		$this->getObject()->type_transaction = VracClient::TRANSACTION_DEFAUT;
    	}
    	if ($values['mercuriale_mois'] && $values['mercuriale_annee']) {
    		$this->getObject()->mercuriale = $values['mercuriale_annee'].'-'.$values['mercuriale_mois'];
    	}
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      
      if ($this->getObject()->mercuriale) {
      	$mercuriale = explode('-', $this->getObject()->mercuriale);
      	if (count($mercuriale) == 2) {
      		$this->setDefault('mercuriale_mois', $mercuriale[1]);
      		$this->setDefault('mercuriale_annee', $mercuriale[0]);
      	}
      }
    }
}