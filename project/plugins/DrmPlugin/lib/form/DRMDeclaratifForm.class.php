<?php

class DRMDeclaratifForm extends BaseForm {

    private $_drm = null;

    /**
     *
     * @param DRM $drm
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;
        parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
    }

    public function getDefaultValues() {
        $has_frequence_paiement = ($this->_drm->declaratif->paiement->douane->frequence) ? 1 : '';
        $default = array(
            'raison_rectificative' => $this->_drm->raison_rectificative,
            'apurement' => $this->_drm->declaratif->defaut_apurement,
            'daa_debut' => $this->_drm->declaratif->daa->debut,
            'daa_fin' => $this->_drm->declaratif->daa->fin,
            'dsa_debut' => $this->_drm->declaratif->dsa->debut,
            'dsa_fin' => $this->_drm->declaratif->dsa->fin,
            'adhesion_emcs_gamma' => $this->_drm->declaratif->adhesion_emcs_gamma,
            'caution' => $this->_drm->declaratif->caution->dispense,
            'frequence' => $this->_drm->declaratif->paiement->douane->frequence,
            'organisme' => $this->_drm->declaratif->caution->organisme,
            'moyen_paiement' => $this->_drm->declaratif->paiement->douane->moyen
        );

        if(!$this->getObject()->getApurementPossible()) {
            $default['apurement'] = 0;
        }
        return $default;
    }

    public function configure() {
        $this->setWidgets(array(
        	'raison_rectificative' => new sfWidgetFormTextarea(),
            'apurement' => new sfWidgetFormChoice(array(
                'expanded' => true,
                'choices' => array(
                    0 => "Pas de défaut d'apurement",
                    1 => "Défaut d'apurement à déclarer (Joindre relevé de non apurement et copie du DAA)"
                ),
                    //'renderer_options' => array('formatter' => array($this, 'formatter'))
            )),
            'daa_debut' => new sfWidgetFormInput(),
            'daa_fin' => new sfWidgetFormInput(),
            'dsa_debut' => new sfWidgetFormInput(),
            'dsa_fin' => new sfWidgetFormInput(),
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
        	'raison_rectificative' => 'Raison de la rectificative :',
            'daa_debut' => 'du',
            'daa_fin' => 'au',
            'dsa_debut' => 'du ',
            'dsa_fin' => 'au',
            'adhesion_emcs_gamma' => 'Adhésion à EMCS/GAMMA (n° non nécessaires)',
            'moyen_paiement' => 'Veuillez sélectionner le moyen de paiement des droits de circulation :',
            'frequence' => 'Veuillez sélectionner votre type d\'échéance :'
        ));
        $this->setValidators(array(
        	'raison_rectificative' => new sfValidatorString(array('required' => false)),
            'apurement' => new sfValidatorChoice(array('required' => true, 'choices' => array(0, 1))),
            'daa_debut' => new sfValidatorInteger(array('required' => false)),
            'daa_fin' => new sfValidatorInteger(array('required' => false)),
            'dsa_debut' => new sfValidatorInteger(array('required' => false)),
            'dsa_fin' => new sfValidatorInteger(array('required' => false)),
            'adhesion_emcs_gamma' => new sfValidatorBoolean(array('required' => false)),
            'caution' => new sfValidatorChoice(array('required' => true, 'choices' => array(1, 0))),
            'organisme' => new sfValidatorString(array('required' => false)),
            'moyen_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array('Numéraire', 'Chèque', 'Virement'))),
            'frequence' => new sfValidatorChoice(array('required' => true, 'choices' => array(DRMPaiement::FREQUENCE_ANNUELLE, DRMPaiement::FREQUENCE_MENSUELLE)))
        ));

        if(!$this->getObject()->getApurementPossible()) {
            $this->setWidget('apurement', new sfWidgetFormInputHidden());
        }
        

        $this->validatorSchema['apurement']->setMessage('required', 'Vous n\'avez pas selectionné de défaut d\'apurement.');
        $this->validatorSchema['caution']->setMessage('required', 'Vous n\'avez pas préciser si vous bénéficier d\'une caution.');
        $this->validatorSchema['organisme']->setMessage('required', 'Veuillez préciser l\'organisme.');
        $this->validatorSchema['moyen_paiement']->setMessage('required', 'Vous n\'avez pas selectionné de moyen de paiement.');
        $this->validatorSchema['frequence']->setMessage('required', 'Vous n\'avez pas selectionné de type d\'échéance.');
        
        $this->validatorSchema['daa_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique pour le début de DAA.');
        $this->validatorSchema['daa_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique pour la fin de DAA.');
        $this->validatorSchema['dsa_debut']->setMessage('invalid', 'Merci d\'entrer une valeur numérique pour le début de DSA.');
        $this->validatorSchema['dsa_fin']->setMessage('invalid', 'Merci d\'entrer une valeur numérique pour la fin DSA.');


        $this->widgetSchema->setNameFormat('drm_declaratif[%s]');
        $this->validatorSchema->setPostValidator(new DRMDeclaratifValidator());
        
        if (!$this->hasWidgetFrequence()) {
            unset($this['frequence']);
        }
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
        $this->_drm->raison_rectificative = $values['raison_rectificative'];
        $this->_drm->declaratif->defaut_apurement = (int) $values['apurement'];
        $this->_drm->declaratif->daa->debut = (int) $values['daa_debut'];
        $this->_drm->declaratif->daa->fin = (int) $values['daa_fin'];
        $this->_drm->declaratif->dsa->debut = (int) $values['dsa_debut'];
        $this->_drm->declaratif->dsa->fin = (int) $values['dsa_fin'];
        $this->_drm->declaratif->adhesion_emcs_gamma = $adhesion_emcs_gamma;
        $this->_drm->declaratif->caution->dispense = (int) $values['caution'];
        $this->_drm->declaratif->caution->organisme = $values['organisme'];
        if ($this->hasWidgetFrequence()) {
            $this->_drm->declaratif->paiement->douane->frequence = $values['frequence'];
        }
        $this->_drm->declaratif->paiement->douane->moyen = $values['moyen_paiement'];
        $this->_drm->save();
        return $this->_drm;
    }

    private function hasWidgetFrequence() {
        return ($this->_drm->declaratif->paiement->douane->frequence && !DRMPaiement::isDebutCampagne()) ? false : true;
    }
    
    public function getObject() {
    	return $this->_drm;
    }

}