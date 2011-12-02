<?php

class DRMMouvementsGenerauxProduitForm extends sfForm {

	public function configure() {
        $this->setWidgets(array(
                'appellation' => new sfWidgetFormChoice(array('choices' => array('appellation1' => 'Appellation 1', 'appellation2' => 'Appellation 2', 'appellation3' => 'Appellation 3', 'appellation4' => 'Appellation 4'))),
        		'couleur' => new sfWidgetFormChoice(array('choices' => array('blanc' => 'Blanc', 'rouge' => 'Rouge'))),
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
        		'appellation' => new sfValidatorChoice(array('required' => true, 'choices' => array('appellation1', 'appellation2', 'appellation3', 'appellation4')),array('required' => 'Champ obligatoire')),
        		'couleur' => new sfValidatorChoice(array('required' => true, 'choices' => array('blanc', 'rouge')),array('required' => 'Champ obligatoire')),
        		'denomination' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
        		'label' => new sfValidatorString(array('required' => false)),
                'disponible' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
        		'stock_vide' => new sfValidatorBoolean(array('required' => false)),
        		'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false))
        ));
		$this->widgetSchema->setNameFormat('produit[%s]');
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}