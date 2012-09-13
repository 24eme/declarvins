<?php
class ExportVrac
{
	protected $vrac;
	protected $configurationVrac;

	public function __construct($vrac, $configurationVrac)
	{
		$this->setVrac($vrac);
		$this->setConfigurationVrac($configurationVrac);
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

    protected static function getPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('vrac_export', 'main')->getPartial('vrac_export/' . $partial, $vars);
    }
}