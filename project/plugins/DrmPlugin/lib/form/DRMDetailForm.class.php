<?php

class DRMDetailForm extends acCouchdbFormDocumentJson {
	protected $_label_choices;

    public function configure() {
        $this->setWidgets(array(
            'label' => new sfWidgetFormChoice(array('multiple' => true, 'choices'  => $this->getLabelChoices())),
            'couleur'      => new sfWidgetFormChoice(array('choices' => array('' => "", 'blanc' => 'Blanc', 'rouge' => 'Rouge', 'rose' => "Rosé"))),
            'label_supplementaire'        => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'label' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getLabelChoices()))),
            'couleur'      => new sfValidatorChoice(array('required' => true, 'choices' => array('blanc', 'rouge', 'rose'))),
            'label_supplementaire'        => new sfValidatorString(array('required' => false)),
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
        $this->getObject()->getAppellation()->couleurs->move($this->getObject()->getCouleur()->getKey().'/cepages/'.$this->getObject()->getCepage()->getKey().'/details/'.$this->getObject()->getKey(), 
                                                             $values['couleur'].'/cepages/'.$this->getObject()->getCepage()->getKey().'/details/'.KeyInflector::slugify($this->getObject()->getLabelKey()));
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