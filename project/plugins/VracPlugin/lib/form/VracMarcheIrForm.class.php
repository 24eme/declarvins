<?php
class VracMarcheIrForm extends VracMarcheForm
{
    public function configure() {
        parent::configure();

        if ($this->isEgalim()) {
            $typePrix1 = array('determine' => 'Déterminé', 'determinable' => 'Déterminable');
        } else {
            $typePrix1 = array('determine' => 'Déterminé', 'non_definitif' => 'Prix non définitif');
        }

		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
		$this->getValidator('delai_paiement')->setOption('required', false);
		$this->setWidget('delai_paiement_autre', new sfWidgetFormInputText());
		$this->getWidget('delai_paiement_autre')->setLabel('Précisez le délai*:');
		$this->setValidator('delai_paiement_autre', new sfValidatorInteger(array('required' => false, 'max' => $this->getObject()->type_transaction == 'raisin'? 30 : 60, 'min' => 1), array('min' => 'La valeur doit être supérieure ou égale à %min%.','max' => 'La valeur doit être inférieure ou égale à %max%.')));

		unset($this['clause_reserve_retiraison']);
		if ($this->getObject()->type_transaction != 'vrac'||!$this->getObject()->premiere_mise_en_marche || $this->getObject()->isPluriannuel()) {
		   unset($this['prix_total_unitaire']);
		}

		$this->setWidget('type_prix_1', new sfWidgetFormChoice(array('expanded' => true, 'choices' => $typePrix1)));
        $this->getWidget('type_prix_1')->setLabel('Type de prix*:');
        $this->setValidator('type_prix_1', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($typePrix1))));


        $this->getWidget('determination_prix')->setLabel('Modalité de fixation du prix déterminé ou de révision du prix*: (celui-ci sera communiqué à l\'interprofession par les parties au contrat)');
        $this->getWidget('determination_prix_date')->setLabel('Date de détermination du prix déterminé*: <a href="" class="msg_aide" data-msg="help_popup_vrac_determination_prix_date" title="Message aide"></a>');

        $this->setWidget('pluriannuel_prix_plancher', new sfWidgetFormInputFloat());
        $this->setWidget('pluriannuel_prix_plafond', new sfWidgetFormInputFloat());
        $this->getWidget('pluriannuel_prix_plancher')->setLabel('Prix plancher (minimum)');
        $this->getWidget('pluriannuel_prix_plafond')->setLabel('Prix plafond (maximum)');
        $this->setValidator('pluriannuel_prix_plancher', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('pluriannuel_prix_plafond', new sfValidatorNumber(array('required' => false)));
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

     if (($values['type_prix_1'] == 'non_definitif' && isset($values['type_prix_2']))) {
         $this->getObject()->type_prix = $values['type_prix_2'];
     } elseif ($values['type_prix_1'] == 'determinable') {
         $this->getObject()->type_prix = 'Déterminable';
     } else {
         $this->getObject()->type_prix = 'Déterminé';
     }


     if ($values['type_prix_1'] == "determine") {
         $this->getObject()->pluriannuel_prix_plancher = null;
         $this->getObject()->pluriannuel_prix_plafond = null;
     }

     if ($values['type_prix_1'] == "determinable" && isset($values['pluriannuel_prix_plancher'])) {
         $this->getObject()->prix_total = null;
         $this->getObject()->prix_total_net = null;
         $this->getObject()->prix_unitaire = null;
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

    public function isEgalim() {
        if ($this->getObject()->type_contrat == VracClient::TYPE_CONTRAT_EGALIM) {
            return true;
        }
    }

}
