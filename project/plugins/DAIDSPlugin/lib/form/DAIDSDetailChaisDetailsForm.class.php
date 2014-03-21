<?php

class DAIDSDetailChaisDetailsForm  extends acCouchdbObjectForm 
{
	protected $_configurationDAIDS;
	
	public function __construct(acCouchdbJson $object, $configurationDAIDS, $options = array(), $CSRFSecret = null) {
		$this->_configurationDAIDS = $configurationDAIDS;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() 
    {
    	$this->setWidget('entrepot_a', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setWidget('entrepot_b', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setWidget('entrepot_c', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setValidator('entrepot_a', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('entrepot_b', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('entrepot_c', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('chais_details[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}