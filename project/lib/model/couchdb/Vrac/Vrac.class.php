<?php
/**
 * Model for Vrac
 *
 */

class Vrac extends BaseVrac {
  public function getProduitConfiguration() {
    return ConfigurationClient::getInstance()->retrieveCurrent()->get($this->produit);
  }
}