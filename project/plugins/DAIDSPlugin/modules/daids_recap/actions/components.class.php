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
    	$items = array();
    	$results = $this->daids_lieu->getCertification()->getLieuxArray();
    	foreach ($results as $key => $result) {
    		if ($result->nbToComplete() > 0) {
    			$items[$key] = $result;
    		}
    	}
    	$this->items = $items;
        
    }

}
