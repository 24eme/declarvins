<?php

class DRMDetailForm extends acCouchdbObjectForm {
	protected $_label_choices;

    public function configure() {
    	$hasTav = false;
    	$hasPremix = false;
    	$inao = $this->getObject()->getCepage()->inao;
		if (!$inao || preg_match('/^1[0-9a-zA-Z]+Z$/', $inao)) {
    		$hasTav = true;
    		$hasPremix = true;
		}
		if (preg_match('/^1[0-9a-zA-Z]+N$/', $inao) || preg_match('/^1[R|S|B]175Z$/', $inao)) {
    		$hasTav = true;
		}
    	if ($hasTav) {
    		$this->setWidget('tav', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	} else {
    		$this->setWidget('tav', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	}
    	if ($hasPremix) {
    		$this->setWidget('premix', new WidgetFormInputCheckbox());
    	} else {
    		$this->setWidget('premix', new WidgetFormInputCheckbox(array(), array('disabled' => 'disabled')));
    	}
    	
    	$this->setValidator('tav', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('premix', new ValidatorBoolean(array('required' => false)));
    	
    	if ($this->getObject()->getDocument()->canSetStockDebutMois()) {
    		$this->setWidget('total_debut_mois', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	} else {
    		$this->setWidget('total_debut_mois', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	}
    	$this->setValidator('total_debut_mois', new sfValidatorNumber(array('required' => false)));
    	

    	if ($this->getObject()->getDocument()->canSetStockDebutMois(true)) {
    		$this->setWidget('acq_total_debut_mois', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	} else {
    		$this->setWidget('acq_total_debut_mois', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	}
    	$this->setValidator('acq_total_debut_mois', new sfValidatorNumber(array('required' => false)));
    	
        $this->stocks_debut = new DRMDetailStocksDebutForm($this->getObject()->stocks_debut, array('acquittes' => false));
        $this->embedForm('stocks_debut', $this->stocks_debut);
            
        $this->entrees = new DRMDetailEntreesForm($this->getObject()->entrees);
        $this->embedForm('entrees', $this->entrees);

        $this->sorties = new DRMDetailSortiesForm($this->getObject()->sorties);
        $this->embedForm('sorties', $this->sorties);
        
        $this->stocks_fin = new DRMDetailStocksFinForm($this->getObject()->stocks_fin, array('acquittes' => false));
        $this->embedForm('stocks_fin', $this->stocks_fin);
        
        $this->widgetSchema->setNameFormat('drm_detail[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if (!$this->getObject()->acq_total_debut_mois) {
        	$defaults = $this->getDefaults();
        	$defaults['acq_total_debut_mois'] = 0;
        	$this->setDefaults($defaults);     
        }
    }
    
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (isset($values["sorties"]["vrac"]) && !$values["sorties"]["vrac"]) {
    		$this->getObject()->remove('vrac');
    		$this->getObject()->add('vrac');
    	}
        if (!$values['acq_total_debut_mois']) {
       		$this->getObject()->acq_total_debut_mois = 0;
       	}
       	if (isset($values['premix']) && $values['premix']) {
       		$this->getObject()->premix = 1;
       	} else {
       		$this->getObject()->premix = 0;       		
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