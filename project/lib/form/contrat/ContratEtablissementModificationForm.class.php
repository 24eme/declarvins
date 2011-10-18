<?php
class ContratEtablissementModificationForm extends sfCouchdbFormDocumentJson {
	
	protected $_douaneCollection = null;
	protected $_familleCollection = null;
	protected $_ediCollection = null;
	
    public function configure() {
	   $douaneChoices = $this->getDouaneChoices();
	   $familleChoices = $this->getFamilleChoices();
	   if (!$this->getObject()->getAdresse())
	   	$sousFamilleChoices = $this->getFamilleSousFamilleChoices();
	   else
	   	$sousFamilleChoices = $this->getSousFamilleChoicesByFamille($this->getObject()->getFamille()); 
	   $sousFamilleValidators = $this->getSousFamilleValidators();
	   
	   $this->setWidgets(array(
		   'raison_sociale' => new sfWidgetFormInputText(),
	       'siret' => new sfWidgetFormInputText(),
	       'cni' => new sfWidgetFormInputText(),
	       'cvi' => new sfWidgetFormInputText(),
	       'no_accises' => new sfWidgetFormInputText(),
	       'no_tva_intracommunautaire' => new sfWidgetFormInputText(),
	       'adresse' => new sfWidgetFormInputText(),
	       'code_postal' => new sfWidgetFormInputText(),
	       'commune' => new sfWidgetFormInputText(),
	       'telephone' => new sfWidgetFormInputText(),
	       'fax' => new sfWidgetFormInputText(),
	       'email' => new sfWidgetFormInputText(),
	       'famille' => new sfWidgetFormChoice(array('choices' => $familleChoices)),
	       'sous_famille' => new sfWidgetFormChoice(array('choices' => $sousFamilleChoices)),
	       'comptabilite_adresse' => new sfWidgetFormInputText(),
	       'comptabilite_code_postal' => new sfWidgetFormInputText(),
	       'comptabilite_commune' => new sfWidgetFormInputText(),
	       'service_douane' => new sfWidgetFormChoice(array('choices' => $douaneChoices))
	   ));
       $this->widgetSchema->setLabels(array(
       	   'raison_sociale' => 'Raison sociale*: ',
	       'siret' => 'SIRET: ',
	       'cni' => 'CNI: ',
	       'cvi' => 'CVI: ',
	       'no_accises' => 'Numéro accises: ',
	       'no_tva_intracommunautaire' => 'Numéro TVA intracommunautaire: ',
	       'adresse' => 'Adresse*: ',
	       'code_postal' => 'Code postal*: ',
	       'commune' => 'Commune*: ',
	       'telephone' => 'Téléphone établissement: ',
	       'fax' => 'Fax établissement: ',
	       'email' => 'Email établissement: ',
	       'famille' => 'Famille*: ',
	       'sous_famille' => 'Sous famille*: ',
	       'comptabilite_adresse' => 'Adresse: ',
	       'comptabilite_code_postal' => 'Code postal: ',
	       'comptabilite_commune' => 'Commune: ',
	       'service_douane' => 'Service douane*: '
       ));
       $this->setValidators(array(
       	   'raison_sociale' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
	       'siret' => new sfValidatorString(array('required' => false, 'max_length' => 15, 'min_length' => 13)),
	       'cni' => new sfValidatorString(array('required' => false, 'max_length' => 13, 'min_length' => 11)),
	       'cvi' => new sfValidatorString(array('required' => false, 'max_length' => 11, 'min_length' => 9)),
	       'no_accises' => new sfValidatorString(array('required' => false)),
	       'no_tva_intracommunautaire' => new sfValidatorString(array('required' => false)),
	       'adresse' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
	       'code_postal' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
	       'commune' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
	       'telephone' => new sfValidatorString(array('required' => false)),
	       'fax' => new sfValidatorString(array('required' => false)),
	       'email' => new sfValidatorString(array('required' => false)),
	       'famille' => new sfValidatorChoice(array('choices' => array_keys($familleChoices))),
	       'sous_famille' => new sfValidatorChoice(array('choices' => $sousFamilleValidators)),
	       'comptabilite_adresse' => new sfValidatorString(array('required' => false)),
	       'comptabilite_code_postal' => new sfValidatorString(array('required' => false)),
	       'comptabilite_commune' => new sfValidatorString(array('required' => false)),
	       'service_douane' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($douaneChoices)))
       ));
       $this->mergePostValidator(new ValidatorXorSiretCni());
		$this->widgetSchema->setNameFormat('contratetablissement[%s]');
    }
    
    /**
     * 
     */
    protected function getDouaneChoices() {
        $douanes = $this->getDouanes();
        $choices = array('' => '');
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
        $choices = array('' => '');
        foreach ($familles as $famille) {
            $choices[$famille] = $famille;
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getFamilleSousFamilleChoices() {
        /*$famillesSousFamilles = $this->getFamillesSousFamilles();
        $choices = array();
        foreach ($famillesSousFamilles as $famille => $sousFamilles) {
            $choices = $this->getSousFamilleChoicesByFamille($famille);
            break;
        }
        return $choices;*/
    	return array('' => '');
    }
    
    /**
     * 
     */
    protected function getSousFamilleChoicesByFamille($famille) {
        $famillesSousFamilles = $this->getFamillesSousFamilles();
        $famillesSousFamilles = $famillesSousFamilles[$famille];
        $choices = array('' => '');
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