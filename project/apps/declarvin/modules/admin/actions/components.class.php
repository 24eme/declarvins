<?php

class adminComponents extends sfComponents {


    public function executeEtablissement_login_form() {
        $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    }


}
