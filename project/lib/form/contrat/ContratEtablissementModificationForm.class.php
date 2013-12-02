<?php
class ContratEtablissementModificationForm extends acCouchdbObjectForm {
	
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
	   
	   $this->setWidgets(array(
               'raison_sociale' => new sfWidgetFormInputText(),
               'nom' => new sfWidgetFormInputText(),
	       'siret' => new sfWidgetFormInputText(array(), array('maxlength' => 14)),
	       'cni' => new sfWidgetFormInputText(array(), array('maxlength' => 12)),
	       'cvi' => new sfWidgetFormInputText(),
	       'no_accises' => new sfWidgetFormInputText(),
	       'no_tva_intracommunautaire' => new sfWidgetFormInputText(),
	       'adresse' => new sfWidgetFormInputText(),
	       'code_postal' => new sfWidgetFormInputText(),
	       'commune' => new sfWidgetFormInputText(),
	       'pays' => new sfWidgetFormInputText(),
	       'telephone' => new sfWidgetFormInputText(),
	       'fax' => new sfWidgetFormInputText(),
	       'email' => new sfWidgetFormInputText(),
	       'famille' => new sfWidgetFormChoice(array('choices' => $familleChoices)),
	       'sous_famille' => new sfWidgetFormChoice(array('choices' => $sousFamilleChoices)),
	       'comptabilite_adresse' => new sfWidgetFormInputText(),
	       'comptabilite_code_postal' => new sfWidgetFormInputText(),
	       'comptabilite_commune' => new sfWidgetFormInputText(),
	       'comptabilite_pays' => new sfWidgetFormInputText(),
	       'service_douane' => new sfWidgetFormChoice(array('choices' => $douaneChoices)),
           'edi' => new sfWidgetFormChoice(array('choices' => array(1 => "Oui", 0 => "Non"),
                                                 'multiple' => false, 'expanded' => true,
                                                      'renderer_options' => array('formatter' => array($this, 'formatter'))
                                                      )),

	   ));
       $this->widgetSchema->setLabels(array(
               'raison_sociale' => 'Raison sociale*: ',
               'nom' => 'Nom commercial*: ',
	       'siret' => 'SIRET**: ',
	       'cni' => 'CNI: ',
	       'cvi' => 'CVI**: ',
	       'no_accises' => 'Numéro accises: ',
	       'no_tva_intracommunautaire' => 'Numéro TVA intracommunautaire: ',
	       'adresse' => 'Adresse*: ',
	       'code_postal' => 'Code postal*: ',
	       'commune' => 'Commune*: ',
	       'pays' => 'Pays*: ',
	       'telephone' => 'Téléphone établissement: ',
	       'fax' => 'Fax établissement: ',
	       'email' => 'Email établissement*: ',
	       'famille' => 'Famille*: ',
	       'sous_famille' => 'Sous famille: ',
	       'comptabilite_adresse' => 'Adresse: ',
	       'comptabilite_code_postal' => 'Code postal: ',
	       'comptabilite_commune' => 'Commune: ',
	       'comptabilite_pays' => 'Pays: ',
	       'service_douane' => 'Service douane*: ',
           'edi' => 'J\'utilise un logiciel agréé EDI Declarvins pour déclarer mes DRM et DAI/DS*'));
       $this->setValidators(array(
       	       'raison_sociale' => new sfValidatorString(array('required' => true)),
       	       'nom' => new sfValidatorString(array('required' => true)),
	       'siret' => new ValidatorSiret(array('required' => false)),
	       'cni' => new ValidatorCni(array('required' => false)),
	       'cvi' => new sfValidatorString(array('required' => false, 'max_length' => 11, 'min_length' => 9)),
	       'no_accises' => new sfValidatorString(array('required' => false)),
	       'no_tva_intracommunautaire' => new sfValidatorString(array('required' => false)),
	       'adresse' => new sfValidatorString(array('required' => true)),
	       'code_postal' => new sfValidatorString(array('required' => true)),
	       'commune' => new sfValidatorString(array('required' => true)),
	       'pays' => new sfValidatorString(array('required' => true)),
	       'telephone' => new sfValidatorString(array('required' => false)),
	       'fax' => new sfValidatorString(array('required' => false)),
	       'email' => new sfValidatorEmailStrict(array('required' => true)),
	       'famille' => new sfValidatorChoice(array('choices' => array_keys($familleChoices))),
	       'sous_famille' => new sfValidatorChoice(array('required' => false, 'choices' => $this->getSousFamilles())),
	       'comptabilite_adresse' => new sfValidatorString(array('required' => false)),
	       'comptabilite_code_postal' => new sfValidatorString(array('required' => false)),
	       'comptabilite_commune' => new sfValidatorString(array('required' => false)),
	       'comptabilite_pays' => new sfValidatorString(array('required' => false)),
	       'service_douane' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($douaneChoices))),
           'edi' => new ValidatorBoolean(array('required' => true))
       ));
       $xorValidator = new ValidatorXor(null, array('field0' => 'siret', 'field1' => 'cni'),
               array('both' => 'LE SIRET est renseigné, vous ne pouvez pas fournir de CNI',
                     'none' => 'Vous devez renseigner obligatoirement le Siret ou le Cni'));
       
       $this->mergePostValidator($xorValidator);
       $this->mergePostValidator(new ValidatorContratEtablissement());
       $this->mergePostValidator(new ValidatorContratDouane());
       $this->widgetSchema->setNameFormat('contratetablissement[%s]');
       }


    protected function updateDefaultsFromObject() {
       parent::updateDefaultsFromObject();
       $this->setDefault('telephone', $this->getObject()->getDocument()->getTelephone());
       $this->setDefault('fax', $this->getObject()->getDocument()->getFax());
    }
    
    /**
     * 
     */
    protected function getDouaneChoices() {
        $douanes = $this->getDouanes();
        $choices = array('' => '');
        foreach ($douanes as $douane) {
        	$value = $douane->value;
            $choices[$value[DouaneAllView::VALUE_DOUANE_NOM]] = $value[DouaneAllView::VALUE_DOUANE_NOM];
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getDouanes() {
        if (!$this->_douaneCollection) {
            return $this->_douaneCollection = DouaneAllView::getInstance()->findActives()->rows;
        }
        else {
            return $this->_douaneCollection;
        }
    }
    
    /**
     * 
     */
    protected function getFamilleChoices() {
        $familles = EtablissementFamilles::getFamilles();
        $choices = array('' => '');
        foreach ($familles as $key => $famille) {
            $choices[$key] = $famille;
        }
        return $choices;
    }
    
    protected function getSousFamilles() {
    	$sousFamilles =  EtablissementFamilles::getSousFamilles();	
    	$result = array();
    	foreach ($sousFamilles as $sousFamillesByFamille) {
    		foreach ($sousFamillesByFamille as $sousFamille => $value) {
    			$result[] = $sousFamille;
    		}
    	}
    	return $result;
    }
    
    /**
     * 
     */
    protected function getFamilleSousFamilleChoices() {
    	return array('' => '');
    }
    
    /**
     * 
     */
    protected function getSousFamilleChoicesByFamille($famille) {
        $famillesSousFamilles = EtablissementFamilles::getSousFamillesByFamille($famille);
        $choices = array('' => '');
        foreach ($famillesSousFamilles as $key => $sousFamilles) {
            $choices[$key] = $sousFamilles;
        }
        return $choices;
    }

    // on surcharge le template par defaut du widget edi (radio)
    public function formatter($widget, $inputs) {
        $rows = array();
        foreach ($inputs as $input) {
            $rows[] = $widget->renderContentTag('span', $input['input'] . $this->getOption('label_separator') . $input['label']);
        }

        return!$rows ? '' : implode($widget->getOption('separator'), $rows);
    }
}