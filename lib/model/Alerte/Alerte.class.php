<?php
class Alerte extends BaseAlerte 
{
	protected $id;
	
	public function __construct($id = null) 
	{
        parent::__construct();
        $this->id = $id;
    }
    
    public function constructId() 
    {
        $this->set('_id', $this->id);
    }
    
    public function save() 
    {
        $this->derniere_detection = date('c');
        parent::save();
    }
    
    public function getLastAlerte() {
    	return $this->alertes->getLast();
    }

}