<?php

class drm_globalComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_labels = ConfigurationClient::getCurrent()->declaration->labels;
        $this->labels = array();
        
        $i = 3;
        foreach ($this->config_labels as $label => $produit) {
            if ($this->getUser()->getDrm()->produits->exist($label)) {
                $this->labels[$i] = $label;
                $i++;
            }
        }
        
        $this->numeros = array(
            'informations' => 1,
            'ajouts-liquidations' => 2,
            'recapitulatif' => 3,
            'validation' => 3 + count($this->labels),
        );
        
        $this->numero = $this->numeros[$this->etape];
        
        $this->numero_validation = $this->numeros['validation'];


        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_labels as $label => $produit) {
                if ($this->getUser()->getDrm()->produits->exist($label))
                    if ($label == $this->label) {
                        break;
                    }
                $this->numero++;
            }
        }
    }

}
