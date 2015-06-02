<?php
class VracConditionIvseForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
		$this->getWidget('conditions_paiement')->setLabel('Conditions de vente:');
		$this->getValidator('conditions_paiement')->setOption('required', false);
        unset($this['delai_paiement']);
		$this->getWidget('reference_contrat_pluriannuel')->setLabel('Si vous avez un contrat pluriannuel écrit, veuillez noter sa référence:');
		$this->setWidget('vin_livre', new sfWidgetFormInputHidden());
        unset($this['clause_reserve_retiraison']);
    }


    public function conditionneReferenceContrat() {
      return false;
    }


    public function conditionneIVSE() {
      return true;
    }
}