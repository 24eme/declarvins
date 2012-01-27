<?php

class DRMMouvementsGenerauxProduitModificationForm extends acCouchdbFormDocumentJson {

	public function configure() {
        $this->setWidgets(array(
        		'pas_de_mouvement' => new sfWidgetFormInputCheckbox()
        ));
        $this->widgetSchema->setLabels(array(
        		'pas_de_mouvement' => 'Pas de mouvement '
        ));

        $this->setValidators(array(
        		'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false))
        ));
		$this->widgetSchema->setNameFormat('produit_'.$this->getObject()->getKey().'[%s]');
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}