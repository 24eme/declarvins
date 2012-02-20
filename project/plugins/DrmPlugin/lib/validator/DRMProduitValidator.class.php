<?php
class DRMProduitValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
    	$this->addRequiredOption('object');
        $this->addOption('throw_global_error', false);
        $this->addMessage('exist', 'Ce(s) label(s) existe(nt) déjà pour cette appellation dans cette couleur');
    }

    protected function doClean($values) 
    {
    	if ($this->existDetail($values)) {
    		if ($this->getOption('throw_global_error')) {
	            throw new sfValidatorError($this, 'exist');
	        }
	        throw new sfValidatorErrorSchema($this, array('label' => new sfValidatorError($this, 'exist')));
    	}
        return $values;
    }
    
    protected function existDetail($values) 
    {
    	return $this->getObject()->getDocument()->exist($values["hashref"].'/details/'.$this->getLabelKey($values['label']));    
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
    	return ($key)? $key : DRM::DEFAULT_KEY;
    }
}