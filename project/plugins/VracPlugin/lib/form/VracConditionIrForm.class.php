<?php
class VracConditionIrForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['annexe']);
        unset($this['premiere_mise_en_marche']);
        unset($this['bailleur_metayer']);
        $this->configurePluriannuel();
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	$this->getObject()->premiere_mise_en_marche = 1;
    }
}