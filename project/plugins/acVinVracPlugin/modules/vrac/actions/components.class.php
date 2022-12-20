<?php
class vracComponents extends sfComponents 
{
    public function executeEtapes() 
    {
        if ($interpro = $this->vrac->getProduitInterpro()) {
            $this->interpro = $interpro;
        } else {
    	   $this->interpro = $this->getInterpro($this->etablissement);
        }
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
		$this->configurationVracEtapes = $this->configurationVrac->getEtapes();
		$this->etapes = $this->configurationVracEtapes->getTabEtapes();
		if (!$this->vrac->has_transaction && isset($this->etapes['transaction'])) {
			unset($this->etapes['transaction']);
		}
    }

    public function executeOngletsPluriannuel()
    {
        $this->contrats = VracHistoryView::getInstance()->findCadreEtApplications($this->vrac->acheteur_identifiant, $this->vrac->_id);

        if ($this->contrats === null) {
            return sfView::NONE;
        }

        usort($this->contrats, function ($a, $b) {
            return $a->id > $b->id;
        });
    }

	public function getInterpro($etablissement)
	{
        if($etablissement) {
            
            return $etablissement->getInterproObject();
        }
		
        return $this->getUser()->getCompte()->getGerantInterpro();
	}
	
	public function getConfigurationVrac($interpro_id = null)
	{
		return ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro_id);
	}
}