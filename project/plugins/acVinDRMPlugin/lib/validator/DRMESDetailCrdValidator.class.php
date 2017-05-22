<?php

class DRMESDetailCrdValidator extends sfValidatorBase {

    protected function doClean($values) {
    	
    	if ($values['volume'] && (!$values['mois'] || !$values['annee'])) {
    		$mess = array();
    		if (!$values['mois']) {
    			$mess['mois'] = new sfValidatorError($this, 'required');
    		}
    		if (!$values['annee']) {
    			$mess['annee'] = new sfValidatorError($this, 'required');
    		}
    		throw new sfValidatorErrorSchema($this, $mess);
    	}
        return $values;
    }

}
