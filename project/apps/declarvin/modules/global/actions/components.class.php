<?php

class globalComponents extends sfComponents {

    public function executeNav() {
        $this->with_etablissement = $this->with_etablissement || ($this->getRoute() instanceof InterfaceEtablissementRoute && $this->getRoute()->getEtablissement());
    }

    public function executeNavBack() {
    	$this->recherche = false;
      $this->configuration = ConfigurationClient::getCurrent();
      $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
    	$this->recherche = true;
    	$this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    	$this->form->setName('etablissement_selection_nav');
    }

    public function executeNavTop() {
        $this->etablissement = null;
        if ($this->getRoute() instanceof InterfaceEtablissementRoute)
            $this->etablissement = $this->getRoute()->getEtablissement();
        if (!$this->etablissement && $this->getRequest()->getParameter('identifiant'))
            $this->etablissement = EtablissementClient::getInstance()->retrieveById($this->getRequest()->getParameter('identifiant'));
    	$this->configuration = ConfigurationClient::getCurrent();
    }

    public function getRoute()
    {
        return $this->getRequest()->getAttribute('sf_route');
    }

}
