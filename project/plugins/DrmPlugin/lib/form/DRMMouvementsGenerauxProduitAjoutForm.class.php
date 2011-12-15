<?php

class DRMMouvementsGenerauxProduitAjoutForm extends acCouchdbFormDocumentJson 
{
	protected $_appellation_choices;
	const NOEUD_TEMPORAIRE = 'tmp';
	
    public function configure() 
    {
        $this->setWidgets(array(
            'appellation' => new sfWidgetFormChoice(array('choices' => $this->getAppellationChoices())),
            'couleur' => new sfWidgetFormChoice(array('choices' => array('' => "", 'blanc' => 'Blanc', 'rouge' => 'Rouge', 'rose' => "Rosé"))),
            'denomination' => new sfWidgetFormInputText(),
            'label' => new sfWidgetFormInputText(),
            'disponible' => new sfWidgetFormInputText(),
            'stock_vide' => new sfWidgetFormInputCheckbox(),
            'pas_de_mouvement' => new sfWidgetFormInputCheckbox()
        ));
        $this->widgetSchema->setLabels(array(
        	'appellation' => 'Appellation*: ',
            'couleur' => 'Couleur*: ',
            'denomination' => 'Dénomination*: ',
            'label' => 'Label: ',
            'disponible' => 'Disponible*: ',
            'stock_vide' => 'Stock vide ',
            'pas_de_mouvement' => 'Pas de mouvement '
        ));
        $this->setValidators(array(
            'appellation' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAppellationChoices())), array('required' => 'Champ obligatoire')),
            'couleur' => new sfValidatorChoice(array('required' => true, 'choices' => array('blanc', 'rouge', 'rose')), array('required' => 'Champ obligatoire')),
            'denomination' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
            'label' => new sfValidatorString(array('required' => false)),
            'disponible' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
            'stock_vide' => new sfValidatorBoolean(array('required' => false)),
            'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false))
        ));
        $this->widgetSchema->setNameFormat('produit[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->getLabelObject()->move(self::NOEUD_TEMPORAIRE.'/'.$this->getObject()->getKey(), $values['appellation'].'/0');
        $this->getObject()->getDocument()->synchroniseDeclaration();
    }
    
    public function getAppellationChoices() 
    {
        if (is_null($this->_appellation_choices)) {
            $this->_appellation_choices = array('' => '');
            foreach ($this->getObject()->getDocument()->declaration->labels->add($this->getObject()->getLabelObject()->getKey())->getConfig()->appellations as $key => $item) {
                if (!$this->getObject()->exist($key)) {
                    $this->_appellation_choices[$key] = $item->getLibelle();
                }
            }
        }
        return $this->_appellation_choices;
    }

}