<?php
class VracMarcheIvseForm extends VracMarcheForm 
{
    public function configure() {
        parent::configure();
		$this->getWidget('conditions_paiement')->setLabel('Conditions de vente:');
		$this->getValidator('conditions_paiement')->setOption('required', false);
		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
		$this->getWidget('conditions_paiement')->setOption('multiple', true);
        $this->getValidator('conditions_paiement')->setOption('multiple', true);
		$this->setWidget('vin_livre', new sfWidgetFormInputHidden());
		if ($this->getObject()->type_transaction == 'raisin') {
		   unset($this['prix_total_unitaire']);
		}
    }
    protected function doUpdateObject($values) {
        if (isset($values['conditions_paiement']) && !empty($values['conditions_paiement']) && is_array($values['conditions_paiement'])) {
            $values['conditions_paiement'] = current($values['conditions_paiement']);
        }
    	parent::doUpdateObject($values);
    	$this->getObject()->has_cotisation_cvo = 1;
    	if ($this->getObject()->type_transaction == 'raisin') {
    		$this->getObject()->prix_total_unitaire = $this->getObject()->prix_unitaire;
    	} 
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('has_cotisation_cvo', 1);

    }

    public function conditionneIVSE() {
      return true;
    }
}