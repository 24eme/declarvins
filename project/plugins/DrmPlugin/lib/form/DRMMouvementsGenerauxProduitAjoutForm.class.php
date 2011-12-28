<?php

class DRMMouvementsGenerauxProduitAjoutForm extends acCouchdbFormDocumentJson 
{
	protected $_appellation_choices;
	protected $_label_choices;
	const NOEUD_TEMPORAIRE = 'tmp';
	const NOEUD_CEPAGE_TEMPORAIRE = 'DEFAUT';
	
    public function configure() 
    {
        $this->setWidgets(array(
        	'cepage' => new sfWidgetFormInputHidden(array(), array('value' => self::NOEUD_CEPAGE_TEMPORAIRE)),
            'appellation' => new sfWidgetFormChoice(array('choices' => $this->getAppellationChoices())),
            'couleur' => new sfWidgetFormChoice(array('choices' => array('' => "", 'blanc' => 'Blanc', 'rouge' => 'Rouge', 'rose' => "Rosé"))),
            'label' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true,'choices' => $this->getLabelChoices())),
            'label_supplementaire' => new sfWidgetFormInputText(),
            'disponible' => new sfWidgetFormInputText(),
            'stock_vide' => new sfWidgetFormInputCheckbox(),
            'pas_de_mouvement' => new sfWidgetFormInputCheckbox()
        ));
        $this->widgetSchema->setLabels(array(
        	'cepage' => 'Cépage*: ',
        	'appellation' => 'Appellation*: ',
            'couleur' => 'Couleur*: ',
            'label' => 'Label: ',
            'label_supplementaire' => 'Label supplémentaire: ',
            'disponible' => 'Disponible*: ',
            'stock_vide' => 'Stock vide ',
            'pas_de_mouvement' => 'Pas de mouvement '
        ));
        $this->setValidators(array(
        	'cepage' => new sfValidatorString(array('required' => true)),
            'appellation' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAppellationChoices())), array('required' => 'Champ obligatoire')),
            'couleur' => new sfValidatorChoice(array('required' => true, 'choices' => array('blanc', 'rouge', 'rose')), array('required' => 'Champ obligatoire')),
            'label' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getLabelChoices()))),
            'label_supplementaire' => new sfValidatorString(array('required' => false)),
            'disponible' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
            'stock_vide' => new sfValidatorBoolean(array('required' => false)),
            'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false))
        ));
        $this->validatorSchema->setPostValidator(new DRMLabelValidator(null, array('object' => $this->getObject())));
        $this->widgetSchema->setNameFormat('produit[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->getCertification()->moveAndClean(self::NOEUD_TEMPORAIRE.'/'.$this->getObject()->getKey(), $values['appellation'].'/'.$this->getObject()->getParent()->getParent()->get($values['appellation'])->count());
        $this->getObject()->getDocument()->synchroniseDeclaration();
    }
    
    public function getAppellationChoices() 
    {
        if (is_null($this->_appellation_choices)) {
            $this->_appellation_choices = array('' => '');
            foreach ($this->getObject()->getDocument()->declaration->certifications->add($this->getObject()->getCertification()->getKey())->getConfig()->appellations as $key => $item) {
                if (!$this->getObject()->exist($key)) {
                    $this->_appellation_choices[$key] = $item->getLibelle();
                }
            }
        }
        return $this->_appellation_choices;
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

}