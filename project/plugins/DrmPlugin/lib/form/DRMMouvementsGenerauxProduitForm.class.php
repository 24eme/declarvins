<?php

class DRMMouvementsGenerauxProduitForm extends acCouchdbFormDocumentJson {

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


        if (!$this->getObject()->getDetail()->hasPasDeMouvement()) {
            $this->getWidget('pas_de_mouvement')->setAttribute('disabled', 'disabled');
        }

		$this->widgetSchema->setNameFormat('produit_'.$this->getObject()->getKey().'[%s]');
    }
    
    public function doUpdateObject($values) {

        parent::doUpdateObject($values);
    }

}