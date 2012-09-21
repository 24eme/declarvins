<?php
class VracConditionCivpForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
		$this->getWidget('conditions_paiement')->setLabel('Conditions de vente:');
		$this->getWidget('reference_contrat_pluriannuel')->setLabel('Si vous avez un contrat pluriannuel écrit, veuillez noter sa référence:');
    }


    public function conditionneReferenceContrat() {
      return false;
    }
}