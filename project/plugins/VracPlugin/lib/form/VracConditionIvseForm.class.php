<?php
class VracConditionIvseForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();

        unset($this['clause_reserve_retiraison']);
    }
}