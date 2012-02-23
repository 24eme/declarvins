<?php
/**
 * Model for ConfigurationAppellation
 *
 */

class ConfigurationAppellation extends BaseConfigurationAppellation {
    
    protected function loadAllData() {
        parent::loadAllData();
        $this->hasCepage();
        $this->hasMillesime();
    }

    public function getCertification() {
        return $this->getParent()->getParent();
    }

    public function getCepagesChoices(array $exclude_key = array())
    {
        $choices = array();
        foreach ($this->couleurs as $couleur_key => $couleur) {
            foreach ($couleur->cepages as $cepage_key => $cepage) {
                if (!in_array($cepage_key, $exclude_key)) {
                    $choices[$couleur_key][$cepage_key] = $cepage->getLibelle();
                }
            }
        }
        
        return $choices;    
    }

    public function hasCepage() {
        return $this->store('has_cepage', array($this, 'hasCepageStore'));
    }

    public function hasMillesime() {
        return $this->store('has_millesime', array($this, 'hasMillesimeStore'));
    }

    public function hasCepageStore() {
        foreach($this->lieux as $lieu) {
            foreach($lieu->couleurs as $couleur) {
                if ($couleur->hasCepage()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasMillesimeStore() {
        foreach($this->lieux as $lieu) {
            foreach($lieu->couleurs as $couleur) {
                if ($couleur->hasMillesime()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getProduits($interpro, $departement = '') {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByAppellation($this->getCertification()->getKey(), $interpro, $departement, $this->getKey());

        foreach($results->rows as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $produits[$item->key[5]] = $libelles;
        }

        return $produits;
    }
    
}