<?php

class globalComponents extends sfComponents {

    public function executeNav() {
        $this->with_etablissement = ($this->getRoute() instanceof InterfaceEtablissementRoute && $this->getRoute()->getEtablissement());
    }

    public function executeNavBack() {
    	$this->recherche = false;
        if ($this->interpro = $this->getUser()->getInterpro()) {
        	$this->recherche = true;
        	$this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
        	$this->form->setName('etablissement_selection_nav');
        }
    }

    public function executeNavTop() {
        $this->etablissement = $this->getRoute()->getEtablissement();
    }

    public function getRoute()
    {
        return $this->getRequest()->getAttribute('sf_route');
    }

}
