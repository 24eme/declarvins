<?php

class TiersLoginForm extends BaseForm { 
    protected $_compte = null;
    protected $_expanded = null;
    
    /**
     *
     * @param Compte $compte
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(_Compte $compte, $expanded = false, $options = array(), $CSRFSecret = null) {
        $this->_compte = $compte;
        $this->_expanded = $expanded;
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
        foreach($this->_compte->tiers as $tiers) {
            $choices[$tiers->id] = $tiers->nom;
        }
        return $choices;
    }
}


