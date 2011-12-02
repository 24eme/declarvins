<?php

class DRMDetailForm extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'denomination' => new sfWidgetFormInputText(),
            'couleur'      => new sfWidgetFormInputText(),
            'label'        => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'denomination' => new sfValidatorString(array('required' => true)),
            'couleur'      => new sfValidatorString(array('required' => true)),
            'label'        => new sfValidatorString(array('required' => false)),
        ));

        $this->stocks = new DRMDetailStocksForm($this->getObject()->stocks);
        $this->embedForm('stocks', $this->stocks);
            
        $this->entrees = new DRMDetailEntreesForm($this->getObject()->entrees);
        $this->embedForm('entrees', $this->entrees);

        $this->sorties = new DRMDetailSortiesForm($this->getObject()->sorties);
        $this->embedForm('sorties', $this->sorties);
        
        $this->widgetSchema->setNameFormat('drm_detail[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->setDefault('couleur', $this->getObject()->getCouleur()->getKey());
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);

        if($this->getObject()->getCouleur()->getKey() != $values['couleur']) {
            $clone = clone $this->getObject();
            $appellation = $this->getObject()->getCouleur()->getAppellation();
            
            $clone->getCouleur()->details->remove($this->getObject()->getKey());
            if (count($clone->getCouleur()->details) == 0) {
                $clone->remove($this->getObject()->getCouleur()->getKey());
            }

            $this->object = $appellation->couleurs->add($values['couleur'])->details->add(null, $this->getObject());
        }
    }
}