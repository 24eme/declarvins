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
		$this->setValidator('delai_paiement', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getDelaisPaiement()))));
		$this->setWidget('delai_paiement_autre', new sfWidgetFormInputText());
		$this->getWidget('delai_paiement_autre')->setLabel('précisez le délai*:');
		$this->setValidator('delai_paiement_autre', new sfValidatorString(array('required' => false)));
		unset($this['clause_reserve_retiraison'], $this['date_debut_retiraison']);
		if ($this->getObject()->type_transaction == 'raisin') {
		   unset($this['prix_total_unitaire']);
		}
        $this->validatorSchema->setPostValidator(new VracMarcheValidator());
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if ($this->getObject()->type_transaction == 'raisin') {
    		$this->getObject()->prix_total_unitaire = $this->getObject()->prix_unitaire;
    	} 
    	$this->getObject()->has_cotisation_cvo = 1;
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('has_cotisation_cvo', 1);

    }
    
    public function isConditionneDelaiPaiement()
    {
        return true;
    }
}