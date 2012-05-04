<?php
/**
 * Model for Vrac
 *
 */

class Vrac extends BaseVrac {
  public function getProduitConfiguration() {
    return ConfigurationClient::getInstance()->retrieveCurrent()->get($this->produit);
  }
  public function save($conn = null) {
    if (!$this->numero) {
      throw sfException('Un objet Vrac se doit d\'avoir un numero');
    }
    if (!$this->_id) {
      $this->_id = 'VRAC-'.$this->numero;
    }
    if ($this->date_creation) {
    	$date = explode('-', $this->date_creation);
    	$this->annee = $date[0];
    	$this->mois = $date[1];
    }
    parent::save($conn);
  }
}