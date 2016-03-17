<?php
class DRMCrdValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
        $this->addRequiredOption('drm');
        $this->addMessage('exist', 'Cette CRD existe déjà !');
    }

    protected function doClean($values) 
    {
        $idCrd = DRMCrd::makeId($values['categorie'], $values['type'], $values['centilisation']);

        if ($this->getDRM()->crds->exist($idCrd)) {
            throw new sfValidatorError($this, 'exist');
        }

        return $values;
    }

    protected function getDRM() 
    {
        return $this->getOption('drm');
    }
}