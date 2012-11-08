<?php

class DRMVracValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->setMessage('invalid', "vous ne pouvez pas, pour un même produit, sélectionner plusieurs fois le même contrat.");
    }

    protected function doClean($values) {
    	$contratIds = array();
    	foreach ($values as $key => $value) {
    		if (is_array($value)) {
	    		foreach ($value['contrats'] as $contrat) {
	    			if (in_array($contrat['vrac'], $contratIds)) {
	    				throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'invalid')));
	    			}
	    			$contratIds[] = $contrat['vrac'];
	    		}	
    		}
    	}
        return $values;
    }

}
