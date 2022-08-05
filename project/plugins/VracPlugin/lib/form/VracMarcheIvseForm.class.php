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

		$this->setWidget('delai_paiement_autre', new sfWidgetFormInputText());
		$this->getWidget('delai_paiement_autre')->setLabel('Précisez le délai*:');
		$this->setValidator('delai_paiement_autre', new sfValidatorString(array('required' => false)));

        unset($this['clause_reserve_retiraison'], $this['vin_livre']);
		if ($this->getObject()->type_transaction != 'vrac'||!$this->getObject()->premiere_mise_en_marche) {
		   unset($this['prix_total_unitaire']);
		}
    }
    protected function doUpdateObject($values) {
        if (isset($values['conditions_paiement']) && !empty($values['conditions_paiement']) && is_array($values['conditions_paiement'])) {
            $values['conditions_paiement'] = current($values['conditions_paiement']);
        }
    	parent::doUpdateObject($values);
    	$this->getObject()->has_cotisation_cvo = 1;
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('has_cotisation_cvo', 1);

    }

    public function getDelaisPaiement()
    {
      $delais = parent::getDelaisPaiement();
      if ($this->getObject()->type_transaction == 'vrac') {
        unset($delais['30_jours']);
      }
      return $delais;
    }

    public function conditionneIVSE() {
      return true;
    }

    public function isConditionneDelaiPaiement()
    {
        return true;
    }
}
