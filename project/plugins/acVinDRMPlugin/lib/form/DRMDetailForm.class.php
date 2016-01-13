<?php

class DRMDetailForm extends acCouchdbObjectForm {
	protected $_label_choices;

    public function configure() {
    	if ($this->getObject()->getDocument()->canSetStockDebutMois()) {
    		$this->setWidget('total_debut_mois', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	} else {
    		$this->setWidget('total_debut_mois', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	}
    	$this->setValidator('total_debut_mois', new sfValidatorNumber(array('required' => false)));
    	
    	
    	if ($this->getObject()->getCertification()->getKey() == ConfigurationProduit::CERTIFICATION_VINSSANSIG) {
    		$this->setWidget('tav', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	} else {
    		$this->setWidget('tav', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	}
    	$this->setValidator('tav', new sfValidatorNumber(array('required' => false)));
    	
        $this->stocks_debut = new DRMDetailStocksDebutForm($this->getObject()->stocks_debut, array('acquittes' => false));
        $this->embedForm('stocks_debut', $this->stocks_debut);
            
        $this->entrees = new DRMDetailEntreesForm($this->getObject()->entrees, array('acquittes' => false));
        $this->embedForm('entrees', $this->entrees);

        $this->sorties = new DRMDetailSortiesForm($this->getObject()->sorties, array('acquittes' => false));
        $this->embedForm('sorties', $this->sorties);
        
        $this->stocks_fin = new DRMDetailStocksFinForm($this->getObject()->stocks_fin, array('acquittes' => false));
        $this->embedForm('stocks_fin', $this->stocks_fin);
        
        $this->widgetSchema->setNameFormat('drm_detail[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (isset($values["sorties"]["vrac"]) && !$values["sorties"]["vrac"]) {
    		$this->getObject()->remove('vrac');
    		$this->getObject()->add('vrac');
    	}
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