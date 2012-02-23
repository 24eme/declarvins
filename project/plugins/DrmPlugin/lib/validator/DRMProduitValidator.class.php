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
        if($this->getObject()->getDocument()->exist($this->getObject()->getHashDetailFromValues($values['hashref'], $values['label']))) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'exist');
            }
            throw new sfValidatorErrorSchema($this, array('label' => new sfValidatorError($this, 'exist')));
        }
        return $values;
    }

    protected function getObject() 
    {
        return $this->getOption('object');
    }

}