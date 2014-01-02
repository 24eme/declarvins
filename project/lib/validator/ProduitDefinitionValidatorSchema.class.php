<?php
class ProduitDefinitionValidatorSchema extends sfValidatorSchema
{
	
	protected  $_object;
	
    public function configure($options = array(), $messages = array()) {
        $this->addMessage('indisponible', "Ce code existe déjà pour ce noeud");
    }

  public function __construct($object, $fields = null, $options = array(), $messages = array())
  {
    $this->_object = $object;
    parent::__construct($fields, $options, $messages);
  }
  protected function doClean($values)
  { 
  	if (isset($values['code']) && !empty($values['code'])) {
  		if ($this->_object->getKey() == Configuration::DEFAULT_KEY && $this->_object->getParent()->exist($values['code'])) {
  			throw new sfValidatorErrorSchema($this, array($this->getOption('code') => new sfValidatorError($this, 'indisponible')));
  		}
  	}
  	if (isset($values['noeud_departements'])) {
	    foreach($values['noeud_departements'] as $key => $value)
	    {
	      if (!$value['departement'])
	      {
	        unset($values['noeud_departements'][$key]);
	      }
	    }
  	}
  	if (isset($values['noeud_droits_douane'])) {
	    foreach($values['noeud_droits_douane'] as $key => $value)
	    {
	      if (!$value['date'])
	      {
	        unset($values['noeud_droits_douane'][$key]);
	      }
	    }
  	}
  	if (isset($values['noeud_droits_cvo'])) {
	    foreach($values['noeud_droits_cvo'] as $key => $value)
	    {
	      if (!$value['date'])
	      {
	        unset($values['noeud_droits_cvo'][$key]);
	      }
	    }
  	}
  	if (isset($values['noeud_labels'])) {
	    foreach($values['noeud_labels'] as $key => $value)
	    {
	      if (!$value['label'] && !$value['code'])
	      {
	        unset($values['noeud_labels'][$key]);
	      }
	    }
  	}
  	if (isset($values['noeud_organismes'])) {
	    foreach($values['noeud_organismes'] as $key => $value)
	    {
	      if (!$value['date'])
	      {
	        unset($values['noeud_organismes'][$key]);
	      }
	    }
  	}
    return $values;
  }
}