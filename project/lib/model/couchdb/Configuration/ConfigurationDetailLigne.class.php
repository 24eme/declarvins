<?php
/**
 * Model for ConfigurationDetailLigne
 *
 */

class ConfigurationDetailLigne extends BaseConfigurationDetailLigne {

  public function isReadable() {

    return ($this->readable);
  }

  public function isWritable() {

    return ($this->readable) && ($this->writable);
  }

  public function getLibelle() {

  	return $this->getDocument()->libelle_detail_ligne->get($this->getParent()->getKey())->get($this->getKey());
  }
  
}