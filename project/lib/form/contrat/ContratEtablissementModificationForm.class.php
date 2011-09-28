<?php
class ContratEtablissementModificationForm extends ContratEtablissementNouveauForm {
	
	protected $_douaneCollection = null;
	protected $_familleCollection = null;
	
    public function configure() {
	   parent::configure();
	   $douaneChoices = $this->getDouaneChoices();
	   $familleChoices = $this->getFamilleChoices();
	   $sousFamilleChoices = $this->getFamilleSousFamilleChoices();
	   $sousFamilleValidators = $this->getSousFamilleValidators();
       $this->setWidget('cni', new sfWidgetFormInputText());
       $this->setWidget('cvi', new sfWidgetFormInputText());
       $this->setWidget('no_accises', new sfWidgetFormInputText());
       $this->setWidget('no_tva_intracommunautaire', new sfWidgetFormInputText());
       $this->setWidget('adresse', new sfWidgetFormInputText());
       $this->setWidget('code_postal', new sfWidgetFormInputText());
       $this->setWidget('commune', new sfWidgetFormInputText());
       $this->setWidget('telephone', new sfWidgetFormInputText());
       $this->setWidget('fax', new sfWidgetFormInputText());
       $this->setWidget('email', new sfWidgetFormInputText());
       $this->setWidget('famille', new sfWidgetFormChoice(array('choices' => $familleChoices)));
       $this->setWidget('sous_famille', new sfWidgetFormChoice(array('choices' => $sousFamilleChoices)));
       $this->setWidget('comptabilite_adresse', new sfWidgetFormInputText());
       $this->setWidget('comptabilite_code_postal', new sfWidgetFormInputText());
       $this->setWidget('comptabilite_commune', new sfWidgetFormInputText());
       $this->setWidget('service_douane', new sfWidgetFormChoice(array('choices' => $douaneChoices)));
       
       $this->getWidget('cni')->setLabel('CNI: ');
       $this->getWidget('cvi')->setLabel('CVI: ');
       $this->getWidget('no_accises')->setLabel('Numéro accises: ');
       $this->getWidget('no_tva_intracommunautaire')->setLabel('Numéro TVA intracommunautaire: ');
       $this->getWidget('adresse')->setLabel('Adresse*: ');
       $this->getWidget('code_postal')->setLabel('Code postal*: ');
       $this->getWidget('commune')->setLabel('Commune*: ');
       $this->getWidget('telephone')->setLabel('Téléphone: ');
       $this->getWidget('fax')->setLabel('Fax: ');
       $this->getWidget('email')->setLabel('Email: ');
       $this->getWidget('famille')->setLabel('Famille*: ');
       $this->getWidget('sous_famille')->setLabel('Sous famille*: ');
       $this->getWidget('comptabilite_adresse')->setLabel('Adresse: ');
       $this->getWidget('comptabilite_code_postal')->setLabel('Code postal: ');
       $this->getWidget('comptabilite_commune')->setLabel('Commune: ');
       $this->getWidget('service_douane')->setLabel('Service douane*: ');
       
       $this->setValidator('cni', new sfValidatorString(array('required' => false)));
       $this->setValidator('cvi', new sfValidatorString(array('required' => false)));
       $this->setValidator('no_accises', new sfValidatorString(array('required' => false)));
       $this->setValidator('no_tva_intracommunautaire', new sfValidatorString(array('required' => false)));
       $this->setValidator('adresse', new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')));
       $this->setValidator('code_postal', new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')));
       $this->setValidator('commune', new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')));
       $this->setValidator('telephone', new sfValidatorString(array('required' => false)));
       $this->setValidator('fax', new sfValidatorString(array('required' => false)));
       $this->setValidator('email', new sfValidatorString(array('required' => false)));
       $this->setValidator('famille', new sfValidatorChoice(array('choices' => array_keys($familleChoices))));
       $this->setValidator('sous_famille', new sfValidatorChoice(array('choices' => $sousFamilleValidators)));
       $this->setValidator('comptabilite_adresse', new sfValidatorString(array('required' => false)));
       $this->setValidator('comptabilite_code_postal', new sfValidatorString(array('required' => false)));
       $this->setValidator('comptabilite_commune', new sfValidatorString(array('required' => false)));
       $this->setValidator('service_douane', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($douaneChoices))));
    }
    
    /**
     * 
     */
    protected function getDouaneChoices() {
        $douanes = $this->getDouanes();
        $choices = array();
        foreach ($douanes as $douane) {
            $choices[$douane->getNom()] = $douane->getNom();
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getDouanes() {
        if (!$this->_douaneCollection) {
            return $this->_douaneCollection = sfCouchdbManager::getClient('Douane')->getAll();
        }
        else {
            return $this->_douaneCollection;
        }
    }
    
    /**
     * 
     */
    protected function getFamilleChoices() {
        $familles = array_keys($this->getFamillesSousFamilles());
        $choices = array();
        foreach ($familles as $famille) {
            $choices[$famille] = $famille;
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getFamilleSousFamilleChoices() {
        $famillesSousFamilles = $this->getFamillesSousFamilles();
        $choices = array();
        foreach ($famillesSousFamilles as $famille => $sousFamilles) {
            $choices = $this->getSousFamilleChoicesByFamille($famille);
            break;
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getSousFamilleChoicesByFamille($famille) {
        $famillesSousFamilles = $this->getFamillesSousFamilles();
        $famillesSousFamilles = $famillesSousFamilles[$famille];
        $choices = array();
        foreach ($famillesSousFamilles as $sousFamilles) {
            $choices[$sousFamilles] = $sousFamilles;
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getFamillesSousFamilles() {
        if (!$this->_familleCollection) {
            return $this->_familleCollection = sfConfig::get('app_etablissements_familles');
        }
        else {
            return $this->_familleCollection;
        }
    }
    
    /**
     * 
     */
    protected function getSousFamilleValidators() {
        $famillesSousFamilles = $this->getFamillesSousFamilles();
        $validators = array();
        foreach ($famillesSousFamilles as $famille => $sousFamilles) {
            $validators = array_merge($validators, $this->getSousFamilleChoicesByFamille($famille));
        }
        return $validators;
    }
	
}