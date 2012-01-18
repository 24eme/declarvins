<?php

class drm_globalComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        $this->certifications = array();
        
        $i = 3;
        foreach ($this->config_certifications as $certification => $produit) {
            if ($this->getUser()->getDrm()->produits->exist($certification)) {
                $this->certifications[$i] = $certification;
                $i++;
            }
        }
        
        $this->numeros = array(
            'informations' => 1,
            'ajouts-liquidations' => 2,
            'recapitulatif' => 3,
            'validation' => 3 + count($this->certifications),
        );
        
        $this->numero = $this->numeros[$this->etape];
        $this->numero_validation = $this->numeros['validation'];


        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification => $produit) {
                if ($this->getUser()->getDrm()->produits->exist($certification))
                    if ($certification == $this->certification) {
                        break;
                    }
                $this->numero++;
            }
        }
    }

}
