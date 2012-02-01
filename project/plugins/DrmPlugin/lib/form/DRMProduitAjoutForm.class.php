<?php

class DRMProduitAjoutForm extends acCouchdbFormDocumentJson 
{
	protected $_appellation_choices;
	protected $_label_choices;

    public function configure() 
    {
        $this->setWidgets(array(
            'produit' => new sfWidgetFormInputText(array(), array('autocomplete-data' => json_encode($this->getProduits()))),
            'appellation' => new sfWidgetFormInputHidden(),
            'couleur' => new sfWidgetFormInputHidden(),
            'cepage' => new sfWidgetFormInputHidden(),
            'millesime' => new sfWidgetFormInputHidden(),
            'label' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true,'choices' => $this->getLabelChoices())),
            'label_supplementaire' => new sfWidgetFormInputText(),
        ));
        $this->widgetSchema->setLabels(array(
            'produits' => 'Produits*: ',
            'label' => 'Label: ',
            'label_supplementaire' => 'Label supplémentaire: ',
        ));

        $this->setValidators(array(
            'produit' => new sfValidatorString(array('required' => true)),
            'appellation' => new sfValidatorString(array('required' => true)),
            'couleur' => new sfValidatorString(array('required' => true)),
            'cepage' => new sfValidatorString(array('required' => true)),
            'millesime' => new sfValidatorString(array('required' => true)),
            'label' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getLabelChoices()))),
            'label_supplementaire' => new sfValidatorString(array('required' => false)),
        ));

        if ($this->hasAppellation()) {
            unset($this['appellation']);
        }

        $this->getProduits();

        $this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('object' => $this->getObject())));
        $this->widgetSchema->setNameFormat('produit_'.$this->getObject()->getCertification()->getKey().'[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!$this->hasAppellation()) {
            $this->getObject()->getCertification()->moveAndClean($this->getObject()->getAppellation()->getKey().'/'.$this->getObject()->getKey(), $this->getAppellation().'/'.$this->getObject()->getParent()->getParent()->add($values['appellation'])->count());
        }
        $this->getObject()->getDocument()->synchroniseDeclaration();
    }
    
    public function getLabelChoices() 
    {
        if (is_null($this->_label_choices)) {
            $this->_label_choices = array();
            foreach (ConfigurationClient::getCurrent()->label as $key => $label) {
            	$this->_label_choices[$key] = $label;
            }
        }

        return $this->_label_choices;
    }

    public function hasAppellation() {

        return $this->getObject()->getAppellation()->getKey() != DRM::NOEUD_TEMPORAIRE;
    }

    public function getAppellation() {
        if ($this->hasAppellation()) {
            return $this->getObject()->getAppellation()->getKey();
        } else {
            return $this->getValue('appellation');
        }
    }

    public function getProduits() {
        $produits = array();
        $config_certification = ConfigurationClient::getCurrent()->declaration
                                                ->certifications
                                                ->get($this->getObject()->getCertification()->getKey());
        foreach($config_certification->appellations as $appellation) {
            foreach($appellation->couleurs as $couleur) {
                foreach($couleur->cepages as $cepage) {
                    foreach($cepage->millesimes as $millesime) {
                        $produits[] = implode('|@', array($appellation->getKey(),
                                                          $appellation->libelle,
                                                          $couleur->getKey(),
                                                          $couleur->libelle,
                                                          $cepage->getKey(),
                                                          $cepage->libelle,
                                                          $millesime->getKey(),
                                                          $millesime->libelle));
                    }
                }
            }
        }

        return $produits;
    }
}