<?php
class Alerte extends BaseAlerte 
{
	protected $_id;
	
	public function __construct($id) 
	{
        parent::__construct();
        $this->_id = $id;
    }
    
    public function constructId() 
    {
        $this->set('_id', $this->_id);
    }
    
    public function doSave() 
    {
        $this->derniere_detection = date('c');
    }

}