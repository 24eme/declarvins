<?php

class daids_recapComponents extends sfComponents {

    public function executeList() 
    {
        $this->produits = $this->daids_lieu->getProduits();
    }
    
    public function executeItemForm() 
    {
        if (is_null($this->form)) {
            $this->form = new DAIDSDetailForm($this->detail, $this->configurationDAIDS);
        }
    }
    
    public function executeOnglets() 
    {
        $this->items = $this->daids_lieu->getCertification()->getLieuxArray();
    }

}
