<?php

class DRMRnaValidator extends sfValidatorSchema
{

    protected function doClean($values)
    {
    	if (!$values['numero'] && ($values['accises'] || $values['date'])) {
    		throw new sfValidatorErrorSchema($this, array('numero' => new sfValidatorError($this, 'required')));
    	}
        return $values;

    }

}
