<?php

class DRMMouvementsGenerauxProduitModificationForm extends acCouchdbFormDocumentJson {

	public function configure() {
        $this->setWidgets(array(
        		'stock_vide' => new sfWidgetFormInputCheckbox(),
        		'pas_de_mouvement' => new sfWidgetFormInputCheckbox()
        ));
        $this->widgetSchema->setLabels(array(
        		'stock_vide' => 'Stock vide ',
        		'pas_de_mouvement' => 'Pas de mouvement '
        ));

        $this->setValidators(array(
        		'stock_vide' => new sfValidatorBoolean(array('required' => false)),
        		'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false))
        ));
		$this->widgetSchema->setNameFormat('produit[%s]');
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}