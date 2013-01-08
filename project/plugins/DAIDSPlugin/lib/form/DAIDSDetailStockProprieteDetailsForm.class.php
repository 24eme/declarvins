<?php

class DAIDSDetailStockProprieteDetailsForm  extends acCouchdbObjectForm 
{
	protected $_configurationDAIDS;
	
	public function __construct(acCouchdbJson $object, $configurationDAIDS, $options = array(), $CSRFSecret = null) {
		$this->_configurationDAIDS = $configurationDAIDS;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() 
    {
    	$this->setWidget('reserve', new sfWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
    	$this->setWidget('vrac_vendu', new sfWidgetFormInputFloat());
    	$this->setWidget('vrac_libre', new sfWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
    	$this->setWidget('conditionne', new sfWidgetFormInputFloat());
    	$this->setValidator('reserve', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('vrac_vendu', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('vrac_libre', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('conditionne', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('stock_propriete_details[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}