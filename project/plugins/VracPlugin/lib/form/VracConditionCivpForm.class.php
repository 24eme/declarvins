<?php
class VracConditionCivpForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        $this->getWidget('echeancier_paiement')->setLabel('Echéancier de facture:');
    }

    public function isEcheanchierPaiementOptionnel() {

        return false;
    }
}