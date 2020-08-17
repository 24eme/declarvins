<?php
class DRMCrdValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
        $this->addRequiredOption('drm');
        $this->addMessage('exist', 'Cette CRD existe déjà');
        $this->addMessage('perso', 'Vous ne pouvez pas ajouter de CRD de type Personnalisées avec une CRD de type Collectives en droits acquittés');
        $this->addMessage('collective', 'Vous ne pouvez pas ajouter de CRD de type Collectives en droits acquittés avec une CRD de type Personnalisées');
        $this->addMessage('centilitre', 'Vous devez renseigner la centilisation pour Autre');
        $this->addMessage('exist_centilisation', 'Veuillez sélectionner la centilisation préconfigurée plutôt que de la saisir');
        
    }

    protected function doClean($values) 
    {
        $idCrd = DRMCrd::makeId($values['categorie'], $values['type'], $values['centilisation'], $values['centilitre'], $values['bib']);
        
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
        
        if ($values['centilisation'] == 'AUTRE' && !$values['centilitre']) {
        	throw new sfValidatorError($this, 'centilitre');
        }

        if ($this->getDRM()->crds->exist($idCrd)) {
            throw new sfValidatorError($this, 'exist');
        }
        
        if ($values['centilitre']) {
        	$centilisation = ($values['bib'])? 'BIB_'.str_replace(array('.', ','), '_', $values['centilitre']) : 'CL_'.str_replace(array('.', ','), '_', $values['centilitre']);
        	$conf = ConfigurationClient::getCurrent($this->getDRM()->getDateDebutPeriode());
        	if ($conf->crds->centilisation->exist($centilisation)) {
        		throw new sfValidatorError($this, 'exist_centilisation');
        	}
        }

        return $values;
    }

    protected function getDRM() 
    {
        return $this->getOption('drm');
    }
}