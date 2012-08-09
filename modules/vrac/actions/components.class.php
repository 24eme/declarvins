<?php
class vracComponents extends sfComponents 
{
    public function executeEtapes() 
    {
    	$this->interpro = $this->getInterpro();
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
		$this->configurationVracEtapes = $this->configurationVrac->getEtapes();
		$this->etapes = $this->configurationVracEtapes->getTabEtapes();
		if (!$this->vrac->has_transaction && isset($this->etapes['transaction'])) {
			unset($this->etapes['transaction']);
		}
    }
	
	public function getInterpro()
	{
		return $this->getUser()->getInterpro();
	}
	
	public function getConfigurationVrac($interpro_id = null)
	{
		return ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro_id);
	}
}