<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {

    
    protected function loadAllData() {
        parent::loadAllData();
    }

    protected function getLibellesAbstract() {

        return array($this->getKey() => $this->libelle);
    }

    public function getProduits($interpro = 'INTERPRO-inter-rhone', $departement = null) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $produits[] = array_merge($item->value->keys, array('libelles' => $item->value->libelles));
        }

        return $produits;
    }
}