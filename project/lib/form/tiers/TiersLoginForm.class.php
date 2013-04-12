<?php

class TiersLoginForm extends BaseForm { 
    protected $_compte = null;
    protected $_expanded = null;
    protected $_etablissements = null;
    
    /**
     *
     * @param Compte $compte
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(_Compte $compte, $expanded = false, $etablissements = array(), $options = array(), $CSRFSecret = null) {
        $this->_compte = $compte;
        $this->_expanded = $expanded;
        $this->_etablissements = $etablissements;
        $this->checkCompte();
        parent::__construct(array(), $options, $CSRFSecret);
    }
    
    /**
     * 
     */
    protected function checkCompte() {
        if (!$this->_compte) {
            throw new sfException("compte does exist");
        }
    }
    
    public function configure() {
        $this->setWidget("tiers", new sfWidgetFormChoice(array("expanded" => $this->_expanded, "choices" => $this->getChoiceTiers())));
        $this->setValidator("tiers", new sfValidatorChoice(array("choices" => array_keys($this->getChoiceTiers()), 
                                                                 "required" => true)));
        
        $this->getValidator("tiers")->setMessage("required", "Champs obligatoire");
        $this->widgetSchema->setNameFormat('tiers[%s]');
        $this->disableLocalCSRFProtection();
    }

    /**
     * 
     * @return Etablissement;
     */
    public function process() {
        if ($this->isValid()) {
            return acCouchdbManager::getClient()->retrieveDocumentById($this->getValue('tiers'));
        } else {
            throw new sfException("must be valid");
        }
    }
    
    public function getChoiceTiers() {
        $choices = array();
        foreach($this->_etablissements as $tiers) {
        	$libelle = $tiers->raison_sociale;
        	if ($tiers->nom) {
        		$libelle .= ' ('.$tiers->nom.')';
        	}
        	if ($tiers->famille) {
        		$libelle .= ' '.EtablissementFamilles::getFamilleLibelle($tiers->famille);
        	}
        	if ($tiers->siege->commune) {
        		$libelle .= ', '.$tiers->siege->commune;
        	}
            $choices[$tiers->_id] = $libelle;
        }
        return $choices;
    }
}


