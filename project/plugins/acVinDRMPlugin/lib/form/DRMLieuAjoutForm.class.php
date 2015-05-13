<?php

class DRMLieuAjoutForm extends acCouchdbForm {

    protected $_drm = null;
    protected $_config = null;
    protected $_certification = null;
    protected $_configurationProduits = null;

    public function __construct(DRM $drm, Configuration $config, $certification, $configurationProduits = null, $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;
        $this->_config = $config;
        $this->_certification = $certification;
        $this->_configurationProduits = $configurationProduits;
        $defaults = array();
        parent::__construct($drm, $defaults, $options, $CSRFSecret);
    }

    public function setup() {
    	$produits = $this->getProduits();
        $this->setWidgets(array(
            'hash' => new sfWidgetFormChoice(array('choices' => $produits, 'label' => 'Appellation')),
        ));

        $this->setValidators(array(
            'hash' => new sfValidatorChoice(array('choices' => array_keys($produits), 'required' => true), 
                                            array('required' => "Aucune appellation n'a été saisi !")),
        ));

        $this->widgetSchema->setNameFormat('drm_lieu_ajout[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function getCertificationHash()
    {
    	return $this->_drm->declaration->certifications->get($this->_certification)->getHash();
    }

    public function getProduits() 
    {
    	if ($this->_configurationProduits) {
    		return array_merge(array("" => ""), $this->_config->format($this->_configurationProduits->getTotalLieux($this->getCertificationHash(), true), "%g% %a% %m% %l%"));
    	}
    	$etablissement = $this->_drm->getEtablissement();
        return array_merge(array("" => ""), $this->_config->getFormattedLieux($this->getCertificationHash(), $etablissement->getConfigurationZones()));;
    }
    
    public function addLieu() 
    {
        return $this->_drm->getOrAdd($this->values['hash']);
    }

}