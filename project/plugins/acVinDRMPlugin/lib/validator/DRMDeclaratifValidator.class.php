<?php

class DRMDeclaratifValidator extends sfValidatorSchema
{

    protected function doClean($values)
    {
        if ($values['empreinte_debut'] || $values['empreinte_fin'] || $values['empreinte_nb']) {
        	if (!$values['empreinte_debut']) {
        		throw new sfValidatorErrorSchema($this, array('empreinte_debut' => new sfValidatorError($this, 'required')));
        	}
        	if (!$values['empreinte_fin']) {
        		throw new sfValidatorErrorSchema($this, array('empreinte_fin' => new sfValidatorError($this, 'required')));
        	}
        	if (!$values['empreinte_nb']) {
        		throw new sfValidatorErrorSchema($this, array('empreinte_nb' => new sfValidatorError($this, 'required')));
        	}
        }
        if ($values['daa_debut'] || $values['daa_fin'] || $values['daa_nb']) {
        	if (!$values['daa_debut']) {
        		throw new sfValidatorErrorSchema($this, array('daa_debut' => new sfValidatorError($this, 'required')));
        	}
        	if (!$values['daa_fin']) {
        		throw new sfValidatorErrorSchema($this, array('daa_fin' => new sfValidatorError($this, 'required')));
        	}
        	if (!$values['daa_nb']) {
        		throw new sfValidatorErrorSchema($this, array('daa_nb' => new sfValidatorError($this, 'required')));
        	}
        }
        if ($values['dsa_debut'] || $values['dsa_fin'] || $values['dsa_nb']) {
        	if (!$values['dsa_debut']) {
        		throw new sfValidatorErrorSchema($this, array('dsa_debut' => new sfValidatorError($this, 'required')));
        	}
        	if (!$values['dsa_fin']) {
        		throw new sfValidatorErrorSchema($this, array('dsa_fin' => new sfValidatorError($this, 'required')));
        	}
        	if (!$values['dsa_nb']) {
        		throw new sfValidatorErrorSchema($this, array('dsa_nb' => new sfValidatorError($this, 'required')));
        	}
        }

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'required');
        }

        return $values;


    }

}
