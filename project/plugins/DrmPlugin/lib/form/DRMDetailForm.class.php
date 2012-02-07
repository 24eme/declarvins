<?php

class DRMDetailForm extends acCouchdbFormDocumentJson {
	protected $_label_choices;

    public function configure() {
        $this->stocks = new DRMDetailStocksForm($this->getObject()->stocks);
        $this->embedForm('stocks', $this->stocks);
            
        $this->entrees = new DRMDetailEntreesForm($this->getObject()->entrees);
        $this->embedForm('entrees', $this->entrees);

        $this->sorties = new DRMDetailSortiesForm($this->getObject()->sorties);
        $this->embedForm('sorties', $this->sorties);
        
        $this->widgetSchema->setNameFormat('drm_detail[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function doUpdateObject($values) {
        $this->getObject()->getDocument()->update();
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