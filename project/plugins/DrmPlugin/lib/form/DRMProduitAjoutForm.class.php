<?php

class DRMProduitAjoutForm extends acCouchdbFormDocumentJson 
{
	protected $_choices_produits;
	protected $_label_choices;
    protected $_interpro = null;
    const LABEL_AUTRE_KEY = "AUTRE";

    public function __construct(acCouchdbJson $object, $interpro, $options = array(), $CSRFSecret = null) {
		$this->_interpro = $interpro;
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
        $this->setWidgets(array(
            'hashref' => new sfWidgetFormChoice(array('choices' => $this->getProduits())),
            'label' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true,'choices' => $this->getLabels())),
            'label_supplementaire' => new sfWidgetFormInputText(),
            'disponible' => new sfWidgetFormInputFloat(),
        ));
        $this->widgetSchema->setLabels(array(
            'hashref' => 'Produit*: ',
            'label' => 'Label: ',
            'label_supplementaire' => 'Label supplémentaire: ',
        ));

        $this->setValidators(array(
            'hashref' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits())),array('required' => "Aucun produit n'a été saisi !")),
            'label' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getLabels()))),
            'label_supplementaire' => new sfValidatorString(array('required' => false)),
            'disponible' => new sfValidatorNumber(array('required' => false)),
        ));

        $this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('object' => $this->getObject())));
        $this->widgetSchema->setNameFormat('produit_'.$this->getObject()->getCertification()->getKey().'[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        $defaults = $this->getDefaults();

        if ($this->object->label_supplementaire) {
            $defaults['label'][] = self::LABEL_AUTRE_KEY;
        }

        $this->setDefaults($defaults);
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!$this->hasAppellation()) {
            $this->getObject()->getCertification()->moveAndClean($this->getObject()->getAppellation()->getKey().'/'.$this->getObject()->getKey(), $this->getAppellation().'/'.$this->getObject()->getParent()->getParent()->add($this->getAppellation())->count());
        }
        $this->getObject()->getDocument()->synchroniseDeclaration();
        if ($values['disponible']) {
            $this->getObject()->getDetail()->total_debut_mois = $values['disponible'];
            $this->getObject()->getDocument()->update();
        } else {
            $this->getObject()->getDetail()->total_debut_mois = 0;
        }
    }
    
    public function getLabels() 
    {
        $labels = ConfigurationClient::getCurrent()->declaration
                                                         ->certifications
                                                         ->get($this->getObject()->getCertification()->getKey())
                                                         ->getLabels($this->_interpro);
        $labels[self::LABEL_AUTRE_KEY] = "Autre";

        return $labels;
    }

    public function hasAppellation() {

        return $this->getObject()->getAppellation()->getKey() != DRM::NOEUD_TEMPORAIRE;
    }

    public function getAppellation() {
        if ($this->hasAppellation()) {

            return $this->getObject()->getAppellation()->getKey();  
        } else {

            return ConfigurationClient::getCurrent()->get($this->getValue('hashref'))->getAppellation()->getKey();
        } 
    }
    
    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            if ($this->hasAppellation()) {
                $this->_choices_produits = ConfigurationClient::getCurrent()->declaration
                                                             ->certifications
                                                             ->get($this->getObject()->getCertification()->getKey())
                                                             ->appellations
                                                             ->get($this->getObject()->getAppellation()->getKey())
                                                             ->getProduits($this->_interpro); 
            } else {
                $this->_choices_produits = ConfigurationClient::getCurrent()->declaration
                                                             ->certifications
                                                             ->get($this->getObject()->getCertification()->getKey())
                                                             ->getProduits($this->_interpro);
            }

            $this->_choices_produits = array_merge(array("" => ""), array_map(array($this, 'formatProduit'), $this->_choices_produits));
        }

        return $this->_choices_produits;
    }

    public function formatProduit($libelles) {

        return implode(' ', array_filter($libelles));
    }
}