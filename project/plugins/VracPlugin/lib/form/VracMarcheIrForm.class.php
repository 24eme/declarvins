<?php
class VracMarcheIrForm extends VracMarcheForm
{
    public function configure() {
        parent::configure();
		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
		$this->getValidator('delai_paiement')->setOption('required', false);
		$this->setWidget('delai_paiement_autre', new sfWidgetFormInputText());
		$this->getWidget('delai_paiement_autre')->setLabel('Précisez le délai*:');
		$this->setValidator('delai_paiement_autre', new sfValidatorString(array('required' => false)));
		unset($this['clause_reserve_retiraison']);
		if ($this->getObject()->type_transaction != 'vrac'||!$this->getObject()->premiere_mise_en_marche) {
		   unset($this['prix_total_unitaire']);
		}
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->has_cotisation_cvo = 1;
    	if ($values['conditions_paiement'] == ConfigurationVrac::CONDITION_PAIEMENT_ECHEANCIER) {
    	    $this->getObject()->delai_paiement = null;
    	    $this->getObject()->delai_paiement_autre = null;
    	} else {
    	    $this->getObject()->paiements = array();
    	}
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('has_cotisation_cvo', 1);

    }

    public function isConditionneDelaiPaiement()
    {
        return true;
    }

    public function getConditionsPaiement()
    {
      $conditions = parent::getConditionsPaiement();
      $hasEcheancier = false;
      if ($this->getObject()->isPluriannuel()||$this->getObject()->isAdossePluriannuel()) {
        $hasEcheancier = true;
      }
      if (!$hasEcheancier && isset($conditions['echeancier_paiement'])) {
        unset($conditions['echeancier_paiement']);
      }
    	return $conditions;
    }

    public function getDelaisPaiement()
    {
      $delais = parent::getDelaisPaiement();
      if ($this->getObject()->type_transaction == 'raisin') {
        if (isset($delais['45_jours']))
          unset($delais['45_jours']);
        if (isset($delais['60_jours']))
          unset($delais['60_jours']);

      } elseif (isset($delais['30_jours'])) {
        unset($delais['30_jours']);
      }

    	return $delais;
    }


    public function hasAcompteInfo() {
      return ($this->getObject()->type_transaction == 'raisin')? false : true;
    }
}
