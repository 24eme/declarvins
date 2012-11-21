<?php
class DAIDSDetailStocksMoyenForm extends acCouchdbObjectForm 
{
	protected $_configurationDAIDS;
	
	public function __construct(acCouchdbJson $object, $configurationDAIDS, $options = array(), $CSRFSecret = null) {
		$this->_configurationDAIDS = $configurationDAIDS;
        parent::__construct($object, $options, $CSRFSecret);
    }
    
	public function configure() 
    {
    	$this->vinifie = new DAIDSDetailStocksMoyenDetailForm($this->getObject()->vinifie, $this->_configurationDAIDS);
        $this->embedForm('vinifie', $this->vinifie);
        
        $this->non_vinifie = new DAIDSDetailStocksMoyenDetailForm($this->getObject()->non_vinifie, $this->_configurationDAIDS);
        $this->embedForm('non_vinifie', $this->non_vinifie);
        
        $this->conditionne = new DAIDSDetailStocksMoyenDetailForm($this->getObject()->conditionne, $this->_configurationDAIDS);
        $this->embedForm('conditionne', $this->conditionne);
    		    		
        $this->widgetSchema->setNameFormat('stocks_moyen[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}