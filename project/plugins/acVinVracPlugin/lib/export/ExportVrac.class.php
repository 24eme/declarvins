<?php
class ExportVrac
{
	protected $vrac;
	protected $configurationVrac;
	protected $isTransaction;

	public function __construct($vrac, $configurationVrac, $isTransaction = false)
	{
		$this->setVrac($vrac);
		$this->setConfigurationVrac($configurationVrac);
		$this->setIsTransaction($isTransaction);
	}
	
	public function getVrac()
	{
		return $this->vrac;
	}
	public function setVrac($vrac)
	{

		$this->vrac = $vrac;
	}
	
	public function getConfigurationVrac()
	{
		return $this->configurationVrac;
	}
	public function setConfigurationVrac($configurationVrac)
	{

		$this->configurationVrac = $configurationVrac;
	}
	
	public function getisTransaction()
	{
		return $this->isTransaction;
	}
	public function setisTransaction($isTransaction)
	{

		$this->isTransaction = $isTransaction;
	}

    protected static function getPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('vrac_export', 'main')->getPartial('vrac_export/' . $partial, $vars);
    }
}