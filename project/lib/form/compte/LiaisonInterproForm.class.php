<?php

class LiaisonInterproForm extends BaseForm {
    
    protected $_compte = null;
    protected $_interproCollection = null;
    
    /**
     *
     * @param Compte $compte
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(acVinCompte $compte, $options = array(), $CSRFSecret = null) {
        $this->_compte = $compte;
        $this->checkCompte();
        parent::__construct(array('interpro' => array_keys($this->_compte->getInterpro()->toArray())), $options, $CSRFSecret);
    }
    /**
     * 
     */
    public function configure() {
        $choices = $this->getInterprosChoices();
        $this->setWidgets(array(
                'interpro' => new sfWidgetFormChoice(array('expanded' => true,'multiple' => true,'choices' => $choices))
        ));

        $this->setValidators(array(
                'interpro' => new sfValidatorChoice(array('required' => false,'multiple' => true,'choices' => array_keys($choices)))
        ));

        $this->widgetSchema->setNameFormat('interpro[%s]');
    }
    
    /**
     * 
     */
    protected function checkCompte() {
        if (!$this->_compte) {
            throw new sfException("compte does exist");
        }
    }
    
    /**
     * 
     */
    protected function getInterprosChoices() {
        $interpros = $this->getInterpros();
        $choices = array();
        $nbEtablissementByInterpro = $this->_compte->getNbEtablissementByInterproId();
        foreach ($interpros as $interpro) {
            if (isset($nbEtablissementByInterpro[$interpro->get('_id')])) {
                if (($nbChais = $nbEtablissementByInterpro[$interpro->get('_id')]) > 1)
                    $choices[$interpro->get('_id')] = $interpro->getNom().' ('.$nbEtablissementByInterpro[$interpro->get('_id')].' Etablissements liés)';
                else
                    $choices[$interpro->get('_id')] = $interpro->getNom().' ('.$nbEtablissementByInterpro[$interpro->get('_id')].' Etablissement lié)';
            }
            else
                $choices[$interpro->get('_id')] = $interpro->getNom();
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getInterpros() {
        if (!$this->_interproCollection) {
            return $this->_interproCollection = acCouchdbManager::getClient('Interpro')->getAll();
        }
        else {
            return $this->_interproCollection;
        }
    }
    
    /**
     * 
     * @return _Compte
     */
    public function save() {
        $existingInterpros = $this->_compte->getInterpro()->toArray();
        $existingInterprosId = array_keys($existingInterpros);
        $values = $this->getValues();
        $interprosId = ($values['interpro'])? $values['interpro'] : array();
        foreach ($interprosId as $interproId) {
            if (!in_array($interproId, $existingInterprosId)) {
                $this->_compte->interpro->add($interproId)->setStatut(_Compte::STATUT_VALIDATION_ATTENTE);
                $this->_compte->save();
            }
        }
        foreach ($existingInterprosId as $existingInterproId) {
            if (!in_array($existingInterproId, $interprosId)) {
                $this->_compte->interpro->remove($existingInterproId);
                $this->_compte->save();
            }
        }
        return $this->_compte;
    }
}

