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
        $default = array(
        	'date_signee' => $this->_drm->valide->date_signee,
            'apurement' => $this->_drm->declaratif->defaut_apurement,
            'empreinte_debut' => ($this->_drm->declaratif->empreinte->debut)? $this->_drm->declaratif->empreinte->debut : null,
            'empreinte_fin' => ($this->_drm->declaratif->empreinte->fin)? $this->_drm->declaratif->empreinte->fin : null,
            'empreinte_nb' => ($this->_drm->declaratif->empreinte->nb)? $this->_drm->declaratif->empreinte->nb : null,
            'daa_debut' => ($this->_drm->declaratif->daa->debut)? $this->_drm->declaratif->daa->debut : null,
            'daa_fin' => ($this->_drm->declaratif->daa->fin)? $this->_drm->declaratif->daa->fin : null,
            'daa_nb' => ($this->_drm->declaratif->daa->nb)? $this->_drm->declaratif->daa->nb : null,
            'dsa_debut' => ($this->_drm->declaratif->dsa->debut)? $this->_drm->declaratif->dsa->debut : null,
            'dsa_fin' => ($this->_drm->declaratif->dsa->fin)? $this->_drm->declaratif->dsa->fin : null,
            'dsa_nb' => ($this->_drm->declaratif->dsa->nb)? $this->_drm->declaratif->dsa->nb : null,
            'frequence' => ($this->_drm->declaratif->paiement->douane->frequence)? $this->_drm->declaratif->paiement->douane->frequence : DRMPaiement::FREQUENCE_MENSUELLE,
            'statistiques_jus' => $this->_drm->declaratif->statistiques->jus,
            'statistiques_mcr' => $this->_drm->declaratif->statistiques->mcr,
            'statistiques_vinaigre' => $this->_drm->declaratif->statistiques->vinaigre,
        );

        return $default;
    }

    public function configure() {
        $this->setWidgets(array(
        	'date_signee' => new sfWidgetFormDateTime(),
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
            'empreinte_nb' => new sfWidgetFormInput(),
            'daa_debut' => new sfWidgetFormInput(),
            'daa_fin' => new sfWidgetFormInput(),
            'daa_nb' => new sfWidgetFormInput(),
            'dsa_debut' => new sfWidgetFormInput(),
            'dsa_fin' => new sfWidgetFormInput(),
            'dsa_nb' => new sfWidgetFormInput(),
        		'statistiques_jus' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
        		'statistiques_mcr' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
        		'statistiques_vinaigre' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
            'frequence' => new sfWidgetFormChoice(array(
                'expanded' => true,
                'choices' => array(
                    DRMPaiement::FREQUENCE_ANNUELLE => DRMPaiement::FREQUENCE_ANNUELLE,
                    DRMPaiement::FREQUENCE_MENSUELLE => DRMPaiement::FREQUENCE_MENSUELLE
                )
            )),
        ));

        $this->widgetSchema->setLabels(array(
        	'date_signee' => 'Date de signature :',
            'empreinte_debut' => 'du',
            'empreinte_fin' => 'au',
            'daa_debut' => 'du',
            'daa_fin' => 'au',
            'dsa_debut' => 'du ',
            'dsa_fin' => 'au',
            'frequence' => 'Veuillez sélectionner votre type d\'échéance :',
            'statistiques_jus' => 'Quantités de moûts de raisin transformées en jus de raisin',
            'statistiques_mcr' => 'Quantités de moûts de raisin transformées en MCR',
            'statistiques_vinaigre' => 'Quantités de moûts de raisin transformées en vinaigre',
        ));
        $this->setValidators(array(
        	'date_signee' => new sfValidatorDateTime(array('required' => false)),
            'apurement' => new sfValidatorChoice(array('required' => true, 'choices' => array(0, 1))),
            'empreinte_debut' => new sfValidatorString(array('required' => false)),
            'empreinte_fin' => new sfValidatorString(array('required' => false)),
            'empreinte_nb' => new sfValidatorInteger(array('required' => false)),
            'daa_debut' => new sfValidatorString(array('required' => false)),
            'daa_fin' => new sfValidatorString(array('required' => false)),
            'daa_nb' => new sfValidatorInteger(array('required' => false)),
            'dsa_debut' => new sfValidatorString(array('required' => false)),
            'dsa_fin' => new sfValidatorString(array('required' => false)),
            'dsa_nb' => new sfValidatorInteger(array('required' => false)),
            'frequence' => new sfValidatorChoice(array('required' => false, 'choices' => array(DRMPaiement::FREQUENCE_ANNUELLE, DRMPaiement::FREQUENCE_MENSUELLE))),
            'statistiques_jus' => new sfValidatorNumber(array('required' => false)),
            'statistiques_mcr' => new sfValidatorNumber(array('required' => false)),
            'statistiques_vinaigre' => new sfValidatorNumber(array('required' => false))
        ));

        $this->validatorSchema['apurement']->setMessage('required', 'Vous n\'avez pas selectionné de défaut d\'apurement.');
        $this->validatorSchema['frequence']->setMessage('required', 'Vous n\'avez pas selectionné de type d\'échéance.');


        $this->validatorSchema['empreinte_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['empreinte_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['empreinte_nb']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['daa_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['daa_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['daa_nb']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['dsa_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['dsa_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');
        $this->validatorSchema['dsa_nb']->setMessage('invalid', 'Merci d\'entrer une valeur numérique.');


        $rna = new DRMDeclaratifRnaCollectionForm($this->_drm->declaratif->rna);
        $this->embedForm('rna', $rna);



        $observations = new DRMValidationObservationsCollectionForm($this->_drm);
        $this->embedForm('observationsProduits', $observations);

        $observationsCrds = new DRMValidationObservationsCrdCollectionForm($this->_drm);
        $this->embedForm('observationsCrds', $observationsCrds);

        $this->embedForm('reports', new DRMDeclaratifReportForm($this->_drm));

        if (!$this->hasWidgetFrequence()) {
            unset($this['frequence']);
            unset($this['reports']);
        }
        if (DRMPaiement::isDebutCampagne($this->_drm->getMois()) && isset($this['reports'])) {
            unset($this['reports']);
        }


        $this->widgetSchema->setNameFormat('drm_declaratif[%s]');
        $this->validatorSchema->setPostValidator(new DRMDeclaratifValidator());
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

    /**
     *
     * @return DRM
     */
    public function save() {
        $values = $this->getValues();
        $this->_drm->valide->date_signee = $values['date_signee'];
        $this->_drm->declaratif->defaut_apurement = (int) $values['apurement'];
        $this->_drm->declaratif->empreinte->debut = (int) $values['empreinte_debut'];
        $this->_drm->declaratif->empreinte->fin = (int) $values['empreinte_fin'];
        $this->_drm->declaratif->empreinte->nb = (int) $values['empreinte_nb'];
        $this->_drm->declaratif->daa->debut = (int) $values['daa_debut'];
        $this->_drm->declaratif->daa->fin = (int) $values['daa_fin'];
        $this->_drm->declaratif->daa->nb = (int) $values['daa_nb'];
        $this->_drm->declaratif->dsa->debut = (int) $values['dsa_debut'];
        $this->_drm->declaratif->dsa->fin = (int) $values['dsa_fin'];
        $this->_drm->declaratif->dsa->nb = (int) $values['dsa_nb'];
        $this->_drm->declaratif->statistiques->jus = $values['statistiques_jus'];
        $this->_drm->declaratif->statistiques->mcr = $values['statistiques_mcr'];
        $this->_drm->declaratif->statistiques->vinaigre = $values['statistiques_vinaigre'];
        if ($this->hasWidgetFrequence()) {
            $this->_drm->declaratif->paiement->douane->frequence = $values['frequence'];
            $reports = $this->_drm->declaratif->getOrAdd('reports');
            if (isset($values['reports'])) {
		        foreach ($values['reports'] as $code => $report) {
		        	if ($report >= 0) {
		        		$reports->add($code, $report);
		        	}
		        }
            }
        }

        if (!$this->_drm->declaratif->defaut_apurement) {
        	$this->_drm->declaratif->remove('rna');
        	$this->_drm->declaratif->add('rna');
        	$values['rna'] = array();
        }
        if ($rnas = $values['rna']) {
        	foreach ($rnas as $doc) {
        		if ($doc['numero']) {
	        		$rna = $this->_drm->declaratif->rna->getOrAdd($doc['numero']);
	        		$rna->numero = $doc['numero'];
	        		$rna->accises = $doc['accises'];
	        		$rna->date = $doc['date'];
        		}
        	}
        }
        if ($observations = $values['observationsProduits']) {
        	foreach ($observations as $hash => $observation) {
       			$this->_drm->addObservationProduit($hash, $observation['observations']);
        	}
        }
        if ($observationsCrds = $values['observationsCrds']) {
          	foreach ($observationsCrds as $hash => $observation) {
         			$this->_drm->addObservationProduit($hash, $observation['observations']);
          	}
        }
        return $this->_drm;
    }

    public function hasWidgetFrequence() {
    	if (($this->_drm->declaratif->paiement->douane->frequence != 'Annuelle') || DRMPaiement::isDebutCampagne($this->_drm->getMois())) {
    		return true;
    	}
    	if ($precedente = $this->_drm->getPrecedente()) {
    		if (($precedente->declaratif->paiement->douane->frequence != 'Annuelle') || ($precedente->declaratif->paiement->douane->report_paye)) {
    			return true;
    		}
    	}
        return false;
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
