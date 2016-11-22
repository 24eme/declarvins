<?php
/**
 * Model for DRMDetailNoeud
 *
 */

class DRMDetailNoeud extends BaseDRMDetailNoeud {

  // $get_anyway : si le champ n'existe pas ou n'est pas lisible, on retourne 0 et pas une exception
  /*public function get($key, $get_anyway = null) {
    return $this->_get($key);    
  }*/
  
  protected function init($params = array()) {
      parent::init($params);
   
	  $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
	  
      foreach ($this as $key => $entree) {
      	if ($this->getKey() == 'stocks_fin' && $keepStock) {
	   		$this->getParent()->stocks_debut->set($key, $this->get($key));
	  	} 
	  	if (is_object($entree)) {
	  		continue;
	  	}
      	$this->set($key, null);
      }
    }

  public function set($key, $value) {
      
    if (is_object($value)) {
        parent::set($key, $value);
        return ;
    }
    
    parent::set($key, $value);
  }

  public function isModifiedMasterDRM($key) {

    return $this->getDocument()->isModifiedMasterDRM($this->getHash(), $key);
  }
}