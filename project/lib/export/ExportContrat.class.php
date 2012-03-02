<?php
class ExportContrat 
{
	protected $contrat;
	
	public function __construct($contrat)
	{
		$this->setContrat($contrat);
	}
	
	public function getContrat()
	{
		return $this->contrat;
	}
	public function setContrat($contrat)
	{
		$this->contrat = $contrat;
	}

    protected static function getPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('export', 'main')->getPartial('export/' . $partial, $vars);
    }
	
}