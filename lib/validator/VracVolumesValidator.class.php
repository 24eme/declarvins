<?php

class VracVolumesValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "La somme des volumes ne correspond pas au volume total proposé");
    }
    
    protected function doClean($values) {
    	$total = 0;
    	foreach ($values['lots'] as $lot) {
    		foreach ($lot['cuves'] as $cuve) {
    			if ($cuve['volume']) {
    				$total += $cuve['volume'];
    			}
    		}
    	}

        if ($total != $values['volume_propose']) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('lots') => new sfValidatorError($this, 'impossible')));
        }
        
        return $values;
    }

}
