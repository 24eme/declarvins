<?php

class DRMProduitAjoutForm extends acCouchdbFormDocumentJson 
{
	protected $_appellation_choices;
	protected $_label_choices;
    protected $_interpro = null;

    public function __construct(acCouchdbJson $object, $interpro, $options = array(), $CSRFSecret = null) {
		$this->_interpro = $interpro;
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
        $this->setWidgets(array(
            'produit' => new sfWidgetFormInputText(array(), array('autocomplete-data' => json_encode($this->getProduits()))),
            'hashref' => new sfWidgetFormInputHidden(),
            'label' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true,'choices' => $this->getLabels())),
            'label_supplementaire' => new sfWidgetFormInputText(),
            'disponible' => new sfWidgetFormInputFloat(),
        ));
        $this->widgetSchema->setLabels(array(
            'produit' => 'Produit*: ',
            'label' => 'Label: ',
            'label_supplementaire' => 'Label supplémentaire: ',
        ));

        $this->setValidators(array(
            'produit' => new sfValidatorString(array('required' => true)),
            'hashref' => new sfValidatorString(array('required' => true)),
            'label' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getLabels()))),
            'label_supplementaire' => new sfValidatorString(array('required' => false)),
            'disponible' => new sfValidatorNumber(array('required' => false)),
        ));

        $this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('object' => $this->getObject())));
        $this->widgetSchema->setNameFormat('produit_'.$this->getObject()->getCertification()->getKey().'[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!$this->hasAppellation()) {
            $this->getObject()->getCertification()->moveAndClean($this->getObject()->getAppellation()->getKey().'/'.$this->getObject()->getKey(), $this->getAppellation().'/'.$this->getObject()->getParent()->getParent()->add($this->getAppellation())->count());
        }
        $this->getObject()->getDocument()->synchroniseDeclaration();
        if ($values['disponible']) {
            $this->getObject()->getDocument()->update();
        }
    }
    
    public function getLabels() 
    {
        return ConfigurationClient::getCurrent()->declaration
                                                         ->certifications
                                                         ->get($this->getObject()->getCertification()->getKey())
                                                         ->getLabels($this->_interpro);
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
        if ($this->hasAppellation()) {
            $produits = ConfigurationClient::getCurrent()->declaration
                                                         ->certifications
                                                         ->get($this->getObject()->getCertification()->getKey())
                                                         ->appellations
                                                         ->get($this->getObject()->getAppellation()->getKey())
                                                         ->getProduits($this->_interpro); 
        } else {
            $produits = ConfigurationClient::getCurrent()->declaration
                                                         ->certifications
                                                         ->get($this->getObject()->getCertification()->getKey())
                                                         ->getProduits($this->_interpro);
        }

        $produits_flat = array();
        foreach($produits as $hash => $libelles)  {
            $libelle = implode(' ', array_filter($libelles));
            $produits_flat[] = $hash.'|@'.$libelle;
        }

        return $produits_flat;
    }
}