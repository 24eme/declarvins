<?php
class VracConditionIrForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['has_transaction']);
        unset($this['clause_reserve_retiraison']);
        unset($this['date_debut_retiraison']);
        unset($this['annexe']);
        unset($this['premiere_mise_en_marche']);
        unset($this['bailleur_metayer']);
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->premiere_mise_en_marche = 1;
    }
}