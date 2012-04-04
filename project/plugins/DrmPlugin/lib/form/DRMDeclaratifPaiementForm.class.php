<?php

class DRMDeclaratifPaiementForm extends BaseForm 
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
		$default = array(
			'frequence' => $this->_drm->declaratif->paiement->douane->frequence
		);
		return $default;
	}
    public function configure() {
         $this->setWidgets(array(
            'frequence' => new sfWidgetFormChoice(array(
         												'expanded' => true,
            											'choices' => array(
         																DRMPaiement::FREQUENCE_ANNUELLE => DRMPaiement::FREQUENCE_ANNUELLE, 
         																DRMPaiement::FREQUENCE_MENSUELLE => DRMPaiement::FREQUENCE_MENSUELLE
         															)
         											))
        ));
        
        $this->widgetSchema->setLabels(array(
        		'frequence' => 'Selectionnez votre type d\'échéance'
        ));
        $this->setValidators(array(
            'frequence' => new sfValidatorChoice(array('required' => true, 'choices' => array(DRMPaiement::FREQUENCE_ANNUELLE, DRMPaiement::FREQUENCE_MENSUELLE)))
        ));
        $this->widgetSchema->setNameFormat('drm_declaratif_paiement[%s]');
    }
    
    /**
     * 
     * @return DRM
     */
    public function save() {
        $values = $this->getValues();
        $this->_drm->declaratif->paiement->douane->frequence = $values['frequence'];
        $this->_drm->save();
        return $this->_drm;
    }
    
    public function getDrm()
    {
    	return $this->_drm;
    }
}