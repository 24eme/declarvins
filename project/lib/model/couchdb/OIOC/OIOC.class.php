<?php
class OIOC extends BaseOIOC 
{
	const OIOC_KEY = 'OIOC-';
	
	public function __toString() 
  	{
    	return $this->nom;
  	}
  
  	public function getKey() 
  	{
    	return $this->_id;
  	}
}