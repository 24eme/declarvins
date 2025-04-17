<?php
class VracValidationCivpForm extends VracValidationForm 
{
    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->clauses_complementaires = null;
        $this->getObject()->autres_conditions = null;
    }
    
    public function hasClauses() {
        return false;
    }

    public function conditionneIVSE() {
      return false;
    }
}
