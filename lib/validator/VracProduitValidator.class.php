<?php

class VracProduitValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('determination_prix_field', 'determination_prix');
    }
    
    protected function doClean($values) {
        return $values;
    }

}
