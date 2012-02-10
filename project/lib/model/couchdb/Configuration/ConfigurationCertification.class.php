<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {

    
    protected function loadAllData() {
        parent::loadAllData();
        //$this->getProduits();
    }

    protected function getLibellesAbstract() {

        return array($this->getKey() => $this->libelle);
    }

    public function getProduits() {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduits($this->getKey(), 'INTERPRO-inter-rhone');
        foreach($results->rows as $item) {
            $produits[] = array_merge($item->key[5], array('libelles' => $item->value));
        }

        return $produits;
    }
}