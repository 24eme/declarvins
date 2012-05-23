<?php

class globalComponents extends sfComponents {

    public function executeNavBack() {
        $this->interpro = $this->getUser()->getInterpro();
        $this->form = new EtablissementSelectionForm($this->interpro->_id);
        $this->form->setName('etablissement_selection_nav');
    }

}
