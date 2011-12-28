<?php
class DRMLabelValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
    	$this->addRequiredOption('object');
        $this->addOption('label_supplementaire_field', 'label_supplementaire');
        $this->addOption('throw_global_error', false);
        $this->setMessage('invalid', 'Si vous ne selectionnez pas de label, vous devez spécifier un label supplémentaire');
        $this->addMessage('exist', 'Ce(s) label(s) existe(nt) déjà pour cette appellation dans cette couleur');
    }

    protected function doClean($values) 
    {
        if ((isset($values['label']) && !empty($values['label'])) || (isset($values['label_supplementaire']) && !empty($values['label_supplementaire']))) {
        	if ($this->existDetail($values)) {
        		if ($this->getOption('throw_global_error')) {
		            throw new sfValidatorError($this, 'exist');
		        }
		        throw new sfValidatorErrorSchema($this, array($this->getOption('label_supplementaire_field') => new sfValidatorError($this, 'exist')));
        	}
			return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }
        throw new sfValidatorErrorSchema($this, array($this->getOption('label_supplementaire_field') => new sfValidatorError($this, 'invalid')));
    }
    
    protected function existDetail($values) 
    {
    	return $this->getObject()->getDocument()->exist('declaration/certifications/'.$this->getObject()->getCertification()->getKey().'/appellations/'.$values['appellation'].'/couleurs/'.$values['couleur'].'/cepages/'.$values['cepage'].'/details/'.$this->getLabelKey($values['label']));    
    }

    protected function getObject() 
    {
        return $this->getOption('object');
    }
    
	protected function getLabelKey($labels) 
	{
    	$key = null;
    	if ($labels) {
    		$key = implode('-', $labels);
    	}
    	return ($key)? $key : DRMProduit::LABEL_DEFAULT_KEY;
    }
}