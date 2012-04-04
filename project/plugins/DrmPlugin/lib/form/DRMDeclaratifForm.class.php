<?php

class DRMDeclaratifForm extends BaseForm 
{
	private $_drm = null;
    
    /**
     *
     * @param DRM $drm
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) 
    {
        $this->_drm = $drm;
        parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
    }
	public function getDefaultValues()
	{
		$has_frequence_paiement = ($this->_drm->declaratif->paiement->douane->frequence)? 1 : '';
		$default = array(
			'apurement' => $this->_drm->declaratif->defaut_apurement,
			'daa_debut' => $this->_drm->declaratif->daa->debut,
			'daa_fin' => $this->_drm->declaratif->daa->fin,
			'dsa_debut' => $this->_drm->declaratif->dsa->debut,
			'dsa_fin' => $this->_drm->declaratif->dsa->fin,
			'adhesion_emcs_gamma' => $this->_drm->declaratif->adhesion_emcs_gamma,
			'caution' => $this->_drm->declaratif->caution->dispense,
			'organisme' => $this->_drm->declaratif->caution->organisme,
			'has_frequence_paiement' => $has_frequence_paiement,
			'moyen_paiement' => $this->_drm->declaratif->paiement->douane->moyen
		);
		return $default;
	}
    public function configure() {
         $this->setWidgets(array(
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
         	'organisme' => new sfWidgetFormInput(),
         	'has_frequence_paiement' => new sfWidgetFormInputHidden(),
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
        		'daa_debut' => 'du',
        		'daa_fin' => 'au',
        		'dsa_debut' => 'du ',
        		'dsa_fin' => 'au',
        		'adhesion_emcs_gamma' => 'Adhésion à EMCS/GAMMA (n° non nécessaires)',
        		'moyen_paiement' => 'Paiement droit circulation',
        ));
        $this->setValidators(array(
            'apurement' => new sfValidatorChoice(array('required' => true, 'choices' => array(0, 1))),
        	'daa_debut' => new sfValidatorInteger(array('required' => false)),
         	'daa_fin' => new sfValidatorInteger(array('required' => false)),
         	'dsa_debut' => new sfValidatorInteger(array('required' => false)),
         	'dsa_fin' => new sfValidatorInteger(array('required' => false)),
        	'adhesion_emcs_gamma' => new sfValidatorBoolean(array('required' => false)),
            'caution' => new sfValidatorChoice(array('required' => true, 'choices' => array(1, 0))),
        	'organisme' => new sfValidatorString(array('required' => false)),
        	'has_frequence_paiement' => new sfValidatorBoolean(array('required' => true)),
        	'moyen_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array('Numéraire', 'Chèque', 'Virement'))),
        ));
        $this->widgetSchema->setNameFormat('drm_declaratif[%s]');
        $this->validatorSchema->setPostValidator(new DRMDeclaratifValidator());
    }
	/*public function formatter($widget, $inputs)
	{
	    $rows = array();
	    foreach ($inputs as $input)
	    {
	      $rows[] = $widget->renderContentTag('div', $input['label'].$this->getOption('label_separator').$input['input'], array('class' => 'ligne_form'));
	    }
	
	    return !$rows ? '' : implode($widget->getOption('separator'), $rows);
  	}*/
    
    /**
     * 
     * @return DRM
     */
    public function save() {
        $values = $this->getValues();
        $adhesion_emcs_gamma = ($values['adhesion_emcs_gamma'])? 1 : null;
        $this->_drm->declaratif->defaut_apurement = (int)$values['apurement'];
		$this->_drm->declaratif->daa->debut = (int)$values['daa_debut'];
		$this->_drm->declaratif->daa->fin = (int)$values['daa_fin'];
		$this->_drm->declaratif->dsa->debut = (int)$values['dsa_debut'];
		$this->_drm->declaratif->dsa->fin = (int)$values['dsa_fin'];
		$this->_drm->declaratif->adhesion_emcs_gamma = $adhesion_emcs_gamma;
		$this->_drm->declaratif->caution->dispense = (int)$values['caution'];
		$this->_drm->declaratif->caution->organisme = $values['organisme'];
		$this->_drm->declaratif->paiement->douane->moyen = $values['moyen_paiement'];
        $this->_drm->save();
        return $this->_drm;
    }
}