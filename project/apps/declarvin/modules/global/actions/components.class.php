<?php

class globalComponents extends sfComponents {

    public function executeNavBack() {
    	$this->recherche = false;
        if ($this->interpro = $this->getUser()->getInterpro()) {
        	$this->recherche = true;	
        	$this->form = new EtablissementSelectionForm($this->interpro->_id);
        	$this->form->setName('etablissement_selection_nav');
        }
    }

}
