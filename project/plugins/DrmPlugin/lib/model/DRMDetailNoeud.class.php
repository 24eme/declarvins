<?php
/**
 * Model for DRMDetailNoeud
 *
 */

class DRMDetailNoeud extends BaseDRMDetailNoeud {

  // $get_anyway : si le champ n'existe pas ou n'est pas lisible, on retourne 0 et pas une exception
  public function get($key, $get_anyway = null) {
    if (!$this->getConfig()->exist($key) || !$this->getConfig()->get($key)->isReadable()) {
      if ($get_anyway)
	return 0;
      throw new sfException("$key is not readable");
    }
    return $this->_get($key);    
  }

  public function getConfig() {
    return $this->getParent()->getConfig()->get($this->getKey());
  }

  public function set($key, $value) {
    try {
      if (!$value && !$this->get($key))
	return;
    }catch(sfException $e) {
      return ;
    }
    if (!$this->getConfig()->exist($key) && !$this->getConfig()->get($key)->isWritable())
      throw new sfException("$key is not writable");
    return $this->_set($key, $value);
  }
}