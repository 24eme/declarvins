<?php
class VracConditionIvseForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['has_transaction'], $this['annexe'], $this['bailleur_metayer']);
        if ($this->getObject()->vendeur->famille != EtablissementFamilles::FAMILLE_NEGOCIANT && $this->getObject()->vendeur->sous_famille != EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR) {
            unset($this['premiere_mise_en_marche']);
        }
    }

    public function conditionneIVSE() {
      return true;
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->has_transaction = 0;
    }
}