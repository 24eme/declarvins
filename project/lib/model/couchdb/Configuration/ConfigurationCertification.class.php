<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {

    
    protected function loadAllData() {
        parent::loadAllData();
        $this->getProduits();
    }

    protected function getLibellesAbstract() {

        return array($this->getKey() => $this->libelle);
    }

    public function getProduits() {
        return $this->store('produits', array($this, 'getProduitsAbstract'));
    }

    protected function getProduitsAbstract() {
        $produits = array();

        foreach($this->appellations as $appellation) {
            foreach($appellation->couleurs as $couleur) {
                foreach($couleur->cepages as $cepage) {
                    foreach($cepage->millesimes as $millesime) {
                        $produits[] = array($appellation->getKey(),
                                         $couleur->getKey(),
                                         $cepage->getKey(),
                                         $millesime->getKey(),
                                         'libelles' => $millesime->getLibelles());
                    }
                }
            }
        }

        return $produits;
    }
}