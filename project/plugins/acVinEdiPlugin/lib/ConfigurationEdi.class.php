<?php
class ConfigurationEdi
{
	private static $_instance = null;
	protected $type;
	protected $configuration;
	
	public static function getInstance($type)
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new ConfigurationEdi($type);
		}
		return self::$_instance;
	}
	
	public function __construct($type) 
	{
		$this->setType($type);
		$this->configuration = sfConfig::get('edi_configuration_'.$this->type, array());
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getProduits()
	{
		return $this->configuration['get_produits'];
	}
	
}