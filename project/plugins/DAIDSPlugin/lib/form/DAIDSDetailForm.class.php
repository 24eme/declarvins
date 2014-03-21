<?php

class DAIDSDetailForm extends acCouchdbObjectForm 
{
	protected $_configurationDAIDS;
	protected $_label_choices;
	
	public function __construct(acCouchdbJson $object, $configurationDAIDS, $options = array(), $CSRFSecret = null) {
		$this->_configurationDAIDS = $configurationDAIDS;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() 
    {
    	$this->setWidget('total_manquants_excedents', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	$this->setWidget('total_pertes_autorisees', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	$this->setWidget('total_manquants_taxables', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	$this->setWidget('total_douane', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	$this->setWidget('stock_theorique', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setWidget('stock_chais', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	$this->setWidget('stock_propriete', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    	$this->setWidget('stock_mensuel_theorique', new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));

    	$this->setValidator('total_manquants_excedents', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('total_pertes_autorisees', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('total_manquants_taxables', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('total_douane', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('stock_theorique', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('stock_chais', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('stock_propriete', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('stock_mensuel_theorique', new sfValidatorNumber(array('required' => false)));
    	
    	$this->douane = new DAIDSDetailDouaneForm($this->getObject()->douane);
        $this->embedForm('douane', $this->douane);
    	
        $this->stocks = new DAIDSDetailStocksForm($this->getObject()->stocks);
        $this->embedForm('stocks', $this->stocks);
            
        $this->stock_propriete_details = new DAIDSDetailStockProprieteDetailsForm($this->getObject()->stock_propriete_details, $this->_configurationDAIDS);
        $this->embedForm('stock_propriete_details', $this->stock_propriete_details);
        
        $this->chais_details = new DAIDSDetailChaisDetailsForm($this->getObject()->chais_details, $this->_configurationDAIDS);
        $this->embedForm('chais_details', $this->chais_details);

        $this->stocks_moyen = new DAIDSDetailStocksMoyenForm($this->getObject()->stocks_moyen, $this->_configurationDAIDS);
        $this->embedForm('stocks_moyen', $this->stocks_moyen);
        
        $this->widgetSchema->setNameFormat('daids_detail[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function doUpdateObject($values) 
    {
    	parent::doUpdateObject($values);
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