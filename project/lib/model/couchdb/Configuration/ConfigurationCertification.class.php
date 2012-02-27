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

    public function getProduits($interpro, $departement = null) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $produits[$item->key[5]] = $libelles;
        }

        return $produits;
    }

    public function getProduitsAppellations($interpro, $departement = null) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsAppellationsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $produits[$item->key[4]] = $libelles;
        }

        return $produits;
    }
}