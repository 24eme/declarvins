<?php

class DRMProduitAjoutForm extends acCouchdbForm 
{
    protected $_drm = null;
    protected $_config = null;
    protected $_certification = null;
    protected $_lieu = null;
    protected $_configurationProduits = null;

    public function __construct(DRM $drm, Configuration $config, $certification, $lieu = null, $configurationProduits = null, $options = array(), $CSRFSecret = null) {
		$this->_drm = $drm;
        $this->_config = $config;
        $this->_certification = $certification;
        $this->_lieu = $lieu;
        $this->_configurationProduits = $configurationProduits;
        $defaults = array();
        parent::__construct($drm, $defaults, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
    	$produits = $this->getProduits();
    	$labels = $this->getLabels();
        $this->setWidgets(array(
            'hashref' => new sfWidgetFormChoice(array('choices' => $produits)),
            'label' => new sfWidgetFormChoice(array('expanded' => true,'choices' => $labels)),
            'disponible' => new sfWidgetFormInputFloat(array('float_format' => "%01.04f")),
            'libelle' => new sfWidgetFormInput(),
        ));
        $this->widgetSchema->setLabels(array(
            'hashref' => 'Produit: ',
            'label' => 'Label: ',
            'libelle' => 'Libelle CIEL: ',
            'disponible' => 'Volume disponible : ',
        ));

        $this->setValidators(array(
            'hashref' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($produits)),array('required' => "Aucun produit n'a été saisi !")),
            'label' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($labels))),
            'disponible' => new sfValidatorNumber(array('required' => false)),
            'libelle' => new sfValidatorString(array('required' => false)),
        ));

        $this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('drm' => $this->_drm)));
        $this->widgetSchema->setNameFormat('produit_'.$this->_certification.'[%s]');
    }

    public function getDRM() {

        return $this->_drm;
    }
    
    public function getHash()
    {
    	if ($this->_lieu) {
    		return $this->_drm->get($this->_lieu)->getHash();
    	}
    	return $this->_drm->declaration->certifications->get($this->_certification)->getHash();
    }

    public function getLabels() 
    {
        $labels = $this->_config->getLabels($this->_drm->declaration->certifications->get($this->_certification)->getHash());

        return $labels;
    }

    public function hasLabel() {
        
        return count($this->getLabels()) > 0;
    }
    
    public function isProduitApd()
    {
    	return ($this->_certification == ConfigurationProduit::CERTIFICATION_APD);
    }
    
    public function getProduits() 
    {
    	if ($this->_configurationProduits) {
    		return array_merge(array("" => ""), $this->_config->formatWithCode($this->_configurationProduits->getProduits($this->getHash(), false, false, $this->_drm->getFormattedDateFromPeriode(), true), "%g% %a% %m% %l% %co% %ce%"));
    	}
    	$etablissement = $this->_drm->getEtablissement();
    	return array_merge(array("" => ""), $this->_config->getFormattedProduits($this->getHash(), $etablissement->getConfigurationZones(), $etablissement->famille, $etablissement->sous_famille, false, "%g% %a% %m% %l% %co% %ce%", false, $this->_drm->getFormattedDateFromPeriode()));
    }

    public function addProduit() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }
        $hash = $this->values['hashref'];
        if ($this->values['libelle']) {
        	$this->values['label'] = array_merge(array(md5($this->values['libelle'])), $this->values['label']);
        }
        $detail = $this->_drm->addProduit($this->values['hashref'], $this->values['label']);
        $detail->total_debut_mois = 0;
        $detail->acq_total_debut_mois = 0;
        $detail->total_debut_mois_interpro = 0;
        if ($this->values['disponible']) {
            $detail->total_debut_mois = $this->values['disponible'];
            if ($detail->has_vrac) {
        		$detail->total_debut_mois_interpro = $this->values['disponible'];
            }
        }
        if ($this->values['libelle']) {
        	$detail->libelle = $this->values['libelle'];
        }
		$this->_drm->update();
        return $detail;
    }

}