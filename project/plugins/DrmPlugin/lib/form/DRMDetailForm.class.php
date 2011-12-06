<?php

class DRMDetailForm extends acCouchdbFormDocumentJson {

    public function configure() {
        $this->setWidgets(array(
            'denomination' => new sfWidgetFormInputText(),
            'couleur'      => new sfWidgetFormChoice(array('choices' => array('' => "", 'blanc' => 'Blanc', 'rouge' => 'Rouge', 'rose' => "Rosé"))),
            'label'        => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'denomination' => new sfValidatorString(array('required' => true)),
            'couleur'      => new sfValidatorChoice(array('required' => true, 'choices' => array('blanc', 'rouge', 'rose'))),
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
        $this->getObject()->setCouleurValue($values['couleur']);
        $this->getObject()->getDocument()->synchroniseProduits();
        $this->getObject()->getAppellation()->couleurs->move($this->getObject()->getCouleur()->getKey().'/details/'.$this->getObject()->getKey(), 
                                                             $values['couleur'].'/details/'.KeyInflector::slugify($this->getObject()->denomination));
    }
}