<?php
class DAIDSDetailStocksMoyenDetailForm extends acCouchdbObjectForm
{
	protected $_configurationDAIDS;
	
	public function __construct(acCouchdbJson $object, $configurationDAIDS, $options = array(), $CSRFSecret = null) 
	{
		$this->_configurationDAIDS = $configurationDAIDS;
        parent::__construct($object, $options, $CSRFSecret);
    }
    
	public function configure() 
    {
    	if ($this->hasMultiTaux()) {
    		$this->setWidget('taux', new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTaux())));
    		$this->setValidator('taux', new sfValidatorNumber(array('required' => false)));
    	} else {
    		$this->setWidget('taux', new sfWidgetFormInputHidden());
    		$this->setValidator('taux', new sfValidatorNumber(array('required' => false)));
    		
    	}
    	$this->setWidget('total', new sfWidgetFormInputFloat());
    	$this->setValidator('total', new sfValidatorNumber(array('required' => false)));
    	$this->setWidget('volume', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    	$this->setValidator('volume', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }


    protected function updateDefaultsFromObject() 
    {
      parent::updateDefaultsFromObject();  
      $tauxDefaut = (!$this->hasMultiTaux())? $this->getTaux() : $this->_configurationDAIDS->stocks_moyen->get($this->getObject()->getKey())->getFirst()->taux;
      $this->setDefault('taux', $tauxDefaut);
      $this->getObject()->set('taux', $tauxDefaut);
    }
    
    protected function hasMultiTaux() 
    {
    	return (count($this->_configurationDAIDS->stocks_moyen->get($this->getObject()->getKey())) > 1)? true : false;
    }
    
    protected function getTaux() 
    {
    	$taux = array();
    	$valeurTaux = null;
    	foreach ($this->_configurationDAIDS->stocks_moyen->get($this->getObject()->getKey()) as $node) {
    		$t = $node->taux;
    		$taux[$t] = $t.'%';
    		$valeurTaux = $t / 100;
    	}
    	return (count($taux) > 1)? $taux : $valeurTaux;
    }

    public function formatter($widget, $inputs)
    {
      $rows = array();
      foreach ($inputs as $input) {
      	$rows[] = $widget->renderContentTag('li', $input['input']);
      }

      return !$rows ? '' : implode($this->getOption('separator'), $rows);
    }
}