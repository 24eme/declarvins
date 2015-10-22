<?php
class OIOC extends BaseOIOC 
{
	const OIOC_KEY = 'OIOC-';
	
	const STATUT_EDI = "EDI";
	const STATUT_RECEPTIONNE = "RECEPTIONNE";
	const STATUT_TRAITE = "TRAITE";
	
	public function __toString() 
  	{
    	return $this->nom;
  	}
  
  	public function getKey() 
  	{
    	return $this->_id;
  	}
}