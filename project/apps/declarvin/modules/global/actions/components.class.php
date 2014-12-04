<?php

class globalComponents extends sfComponents {

    public function executeNav() {
        $this->with_etablissement = ($this->getRoute() instanceof InterfaceEtablissementRoute && $this->getRoute()->getEtablissement());
    }

    public function executeNavBack() {
    	$this->recherche = false;
        $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
    	$this->recherche = true;
    	$this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    	$this->form->setName('etablissement_selection_nav');
    }

    public function executeNavTop() {
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->configuration = ConfigurationClient::getCurrent();
    }

    public function getRoute()
    {
        return $this->getRequest()->getAttribute('sf_route');
    }

}
