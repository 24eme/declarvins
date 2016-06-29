<?php

class DRMDeclaratifForm extends acCouchdbForm {

    private $_drm = null;

    /**
     *
     * @param DRM $drm
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;
        parent::__construct($drm, $this->getDefaultValues(), $options, $CSRFSecret);
    }

    public function getDefaultValues() {
        $has_frequence_paiement = ($this->_drm->declaratif->paiement->douane->frequence) ? 1 : '';
        $default = array(
            'raison_rectificative' => $this->_drm->raison_rectificative,
        	'date_signee' => $this->_drm->valide->date_signee,
            'apurement' => $this->_drm->declaratif->defaut_apurement,
            'empreinte_debut' => ($this->_drm->declaratif->empreinte->debut)? $this->_drm->declaratif->empreinte->debut : null,
            'empreinte_fin' => ($this->_drm->declaratif->empreinte->fin)? $this->_drm->declaratif->empreinte->fin : null,
            'daa_debut' => ($this->_drm->declaratif->daa->debut)? $this->_drm->declaratif->daa->debut : null,
            'daa_fin' => ($this->_drm->declaratif->daa->fin)? $this->_drm->declaratif->daa->fin : null,
            'dsa_debut' => ($this->_drm->declaratif->dsa->debut)? $this->_drm->declaratif->dsa->debut : null,
            'dsa_fin' => ($this->_drm->declaratif->dsa->fin)? $this->_drm->declaratif->dsa->fin : null,
            'adhesion_emcs_gamma' => $this->_drm->declaratif->adhesion_emcs_gamma,
            'caution' => $this->_drm->declaratif->caution->dispense,
            'frequence' => $this->_drm->declaratif->paiement->douane->frequence,
            'organisme' => $this->_drm->declaratif->caution->organisme,
            'moyen_paiement' => $this->_drm->declaratif->paiement->douane->moyen,
            'statistiques_jus' => $this->_drm->declaratif->statistiques->jus,
            'statistiques_mcr' => $this->_drm->declaratif->statistiques->mcr,
            'statistiques_vinaigre' => $this->_drm->declaratif->statistiques->vinaigre,
            'numero' => $this->_drm->declaratif->caution->numero,
        );

        return $default;
    }

    public function configure() {
        $this->setWidgets(array(
        	'date_signee' => new sfWidgetFormDateTime(),
        	'raison_rectificative' => new sfWidgetFormTextarea(),
            'apurement' => new sfWidgetFormChoice(array(
                'expanded' => true,
                'choices' => array(
                    0 => "Pas de défaut d'apurement",
                    1 => "Défaut d'apurement à déclarer"
                ),
                    //'renderer_options' => array('formatter' => array($this, 'formatter'))
            )),
            'empreinte_debut' => new sfWidgetFormInput(),
            'empreinte_fin' => new sfWidgetFormInput(),
            'daa_debut' => new sfWidgetFormInput(),
            'daa_fin' => new sfWidgetFormInput(),
            'dsa_debut' => new sfWidgetFormInput(),
            'dsa_fin' => new sfWidgetFormInput(),
        		'statistiques_jus' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
        		'statistiques_mcr' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
        		'statistiques_vinaigre' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
            'adhesion_emcs_gamma' => new sfWidgetFormInputCheckbox(),
            'caution' => new sfWidgetFormChoice(array(
                'expanded' => true,
                'choices' => array(
                    1 => "Dispense",
                    0 => "Oui",
                ),
                    //'renderer_options' => array('formatter' => array($this, 'formatter'))
            )),
            'frequence' => new sfWidgetFormChoice(array(
                'expanded' => true,
                'choices' => array(
                    DRMPaiement::FREQUENCE_ANNUELLE => DRMPaiement::FREQUENCE_ANNUELLE,
                    DRMPaiement::FREQUENCE_MENSUELLE => DRMPaiement::FREQUENCE_MENSUELLE
                )
            )),
            'organisme' => new sfWidgetFormInput(),
            'numero' => new sfWidgetFormInput(),
            'moyen_paiement' => new sfWidgetFormChoice(array(
                'expanded' => true,
                'choices' => array(
                    'Numéraire' => "Numéraire",
                    'Chèque' => "Chèque",
                    'Virement' => "Virement"
                ),
                    //'renderer_options' => array('formatter' => array($this, 'formatter'))
            )),
        ));

        $this->widgetSchema->setLabels(array(
        	'date_signee' => 'Date de signature :',
        	'raison_rectificative' => 'Raison de la rectificative :',
            'empreinte_debut' => 'du',
            'empreinte_fin' => 'au',
            'daa_debut' => 'du',
            'daa_fin' => 'au',
            'dsa_debut' => 'du ',
            'dsa_fin' => 'au',
            'adhesion_emcs_gamma' => 'Adhésion à EMCS/GAMMA (n° non nécessaires)',
            'moyen_paiement' => 'Veuillez sélectionner le moyen de paiement des droits de circulation :',
            'frequence' => 'Veuillez sélectionner votre type d\'échéance :',
            'statistiques_jus' => 'Quantités de moûts de raisin transformées en jus de raisin',
            'statistiques_mcr' => 'Quantités de moûts de raisin transformées en MCR',
            'statistiques_vinaigre' => 'Quantités de moûts de raisin transformées en vinaigre',
        ));
        $this->setValidators(array(
        	'date_signee' => new sfValidatorDateTime(array('required' => false)),
        	'raison_rectificative' => new sfValidatorString(array('required' => false)),
            'apurement' => new sfValidatorChoice(array('required' => true, 'choices' => array(0, 1))),
            'empreinte_debut' => new sfValidatorInteger(array('required' => false)),
            'empreinte_fin' => new sfValidatorInteger(array('required' => false)),
            'daa_debut' => new sfValidatorInteger(array('required' => false)),
            'daa_fin' => new sfValidatorInteger(array('required' => false)),
            'dsa_debut' => new sfValidatorInteger(array('required' => false)),
            'dsa_fin' => new sfValidatorInteger(array('required' => false)),
            'adhesion_emcs_gamma' => new sfValidatorBoolean(array('required' => false)),
            'caution' => new sfValidatorChoice(array('required' => true, 'choices' => array(1, 0))),
            'organisme' => new sfValidatorString(array('required' => false)),
            'numero' => new sfValidatorString(array('required' => false)),
            'moyen_paiement' => new sfValidatorChoice(array('required' => false, 'choices' => array('Numéraire', 'Chèque', 'Virement'))),
            'frequence' => new sfValidatorChoice(array('required' => false, 'choices' => array(DRMPaiement::FREQUENCE_ANNUELLE, DRMPaiement::FREQUENCE_MENSUELLE))),
            'statistiques_jus' => new sfValidatorNumber(array('required' => false)),
            'statistiques_mcr' => new sfValidatorNumber(array('required' => false)),
            'statistiques_vinaigre' => new sfValidatorNumber(array('required' => false))
        ));

        $this->validatorSchema['apurement']->setMessage('required', 'Vous n\'avez pas selectionné de défaut d\'apurement.');
        $this->validatorSchema['caution']->setMessage('required', 'Vous n\'avez pas précisé si vous bénéficier d\'une caution.');
        $this->validatorSchema['organisme']->setMessage('required', 'Veuillez préciser l\'organisme.');
        $this->validatorSchema['numero']->setMessage('required', 'Veuillez préciser le numéro.');
        $this->validatorSchema['moyen_paiement']->setMessage('required', 'Vous n\'avez pas selectionné de moyen de paiement.');
        $this->validatorSchema['frequence']->setMessage('required', 'Vous n\'avez pas selectionné de type d\'échéance.');
        
        $this->validatorSchema['empreinte_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['empreinte_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['daa_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['daa_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['dsa_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['dsa_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        

        $rna = new DRMDeclaratifRnaCollectionForm($this->_drm->declaratif->rna);
        $this->embedForm('rna', $rna);
        


        $observations = new DRMValidationObservationsCollectionForm($this->_drm);
        $this->embedForm('observationsProduits', $observations);


        $this->widgetSchema->setNameFormat('drm_declaratif[%s]');
        $this->validatorSchema->setPostValidator(new DRMDeclaratifValidator());
        
        if (!$this->hasWidgetFrequence()) {
            unset($this['frequence']);
        }
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
            if($form instanceof FormBindableInterface) {
                $form->bind($taintedValues[$key], $taintedFiles[$key]);
                $this->updateEmbedForm($key, $form);
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }
    


    public function updateEmbedForm($name, $form) {
    	$this->widgetSchema[$name] = $form->getWidgetSchema();
    	$this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    /* public function formatter($widget, $inputs)
      {
      $rows = array();
      foreach ($inputs as $input)
      {
      $rows[] = $widget->renderContentTag('div', $input['label'].$this->getOption('label_separator').$input['input'], array('class' => 'ligne_form'));
      }

      return !$rows ? '' : implode($widget->getOption('separator'), $rows);
      } */

    /**
     * 
     * @return DRM
     */
    public function save() {
        $values = $this->getValues();
        $adhesion_emcs_gamma = ($values['adhesion_emcs_gamma']) ? 1 : null;
        $this->_drm->valide->date_signee = $values['date_signee'];
        $this->_drm->raison_rectificative = $values['raison_rectificative'];
        $this->_drm->declaratif->defaut_apurement = (int) $values['apurement'];
        $this->_drm->declaratif->empreinte->debut = (int) $values['empreinte_debut'];
        $this->_drm->declaratif->empreinte->fin = (int) $values['empreinte_fin'];
        $this->_drm->declaratif->daa->debut = (int) $values['daa_debut'];
        $this->_drm->declaratif->daa->fin = (int) $values['daa_fin'];
        $this->_drm->declaratif->dsa->debut = (int) $values['dsa_debut'];
        $this->_drm->declaratif->dsa->fin = (int) $values['dsa_fin'];
        $this->_drm->declaratif->adhesion_emcs_gamma = $adhesion_emcs_gamma;
        $this->_drm->declaratif->caution->dispense = (int) $values['caution'];
        $this->_drm->declaratif->caution->organisme = $values['organisme'];
        $this->_drm->declaratif->statistiques->jus = $values['statistiques_jus'];
        $this->_drm->declaratif->statistiques->mcr = $values['statistiques_mcr'];
        $this->_drm->declaratif->statistiques->vinaigre = $values['statistiques_vinaigre'];
        $this->_drm->declaratif->caution->numero = $values['numero'];
        		
        if ($this->hasWidgetFrequence()) {
            $this->_drm->declaratif->paiement->douane->frequence = $values['frequence'];
        }
        $this->_drm->declaratif->paiement->douane->moyen = $values['moyen_paiement'];
        
        if (!$this->_drm->declaratif->defaut_apurement) {
        	$this->_drm->declaratif->remove('rna');
        	$this->_drm->declaratif->add('rna');
        	$values['rna'] = array();
        }
        if ($rnas = $values['rna']) {
        	foreach ($rnas as $doc) {
        		$rna = $this->_drm->declaratif->rna->getOrAdd($doc['numero']);
        		$rna->numero = $doc['numero'];
        		$rna->accises = $doc['accises'];
        		$rna->date = $doc['date'];
        	}
        }
        if ($observations = $values['observationsProduits']) {
        	foreach ($observations as $hash => $observation) {
       			$this->_drm->addObservationProduit($hash, $observation['observations']);
        	}
        }
        //$this->_drm->save();
        return $this->_drm;
    }

    private function hasWidgetFrequence() {
        return ($this->_drm->declaratif->paiement->douane->frequence && !DRMPaiement::isDebutCampagne($this->_drm->getMois())) ? false : true;
    }
    
    public function getObject() {
    	return $this->_drm;
    }
    


    public function getFormTemplateRna()
    {
    	$object = $this->_drm->declaratif->rna->add();
    	$form_embed = new DRMDeclaratifRnaForm($object);
    	$form = new DRMRnaCollectionTemplateForm($this, $form_embed, 'var---nbItem---');
    
    	return $form->getFormTemplate();
    }

}