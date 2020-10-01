<?php
class VracConditionCivpForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['reference_contrat_pluriannuel']);
        if ($this->getObject()->vendeur->famille != EtablissementFamilles::FAMILLE_NEGOCIANT && $this->getObject()->vendeur->sous_famille != EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR) {
            unset($this['premiere_mise_en_marche']);
        }
    }
}