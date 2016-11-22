<?php
class DRMCrdValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
        $this->addRequiredOption('drm');
        $this->addMessage('exist', 'Cette CRD existe déjà !');
        $this->addMessage('perso', 'Vous ne pouvez pas ajouter de CRD de type Personnalisées avec une CRD de type Collectives en droits acquittés');
        $this->addMessage('collective', 'Vous ne pouvez pas ajouter de CRD de type Collectives en droits acquittés avec une CRD de type Personnalisées');
    }

    protected function doClean($values) 
    {
        $idCrd = DRMCrd::makeId($values['categorie'], $values['type'], $values['centilisation']);
        
        if ($values['type'] == 'PERSONNALISEES') {
        	foreach ($this->getDRM()->crds as $crd) {
        		if ($crd->type->code == 'COLLECTIVES_DROITS_ACQUITTES') {
        			throw new sfValidatorError($this, 'perso');
        		}
        	}
        }
        if ($values['type'] == 'COLLECTIVES_DROITS_ACQUITTES') {
        	 foreach ($this->getDRM()->crds as $crd) {
        	 	if ($crd->type->code == 'PERSONNALISEES') {
            		throw new sfValidatorError($this, 'collective');
        	 	}
        	 }
        }

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