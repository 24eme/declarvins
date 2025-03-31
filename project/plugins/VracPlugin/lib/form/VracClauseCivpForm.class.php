<?php

class VracClauseCivpForm extends VracClauseForm
{
    public function configure() {
        parent::configure();
        unset($this['autres_conditions'], $this['clause_initiative_contractuelle_producteur']);
    }
}
