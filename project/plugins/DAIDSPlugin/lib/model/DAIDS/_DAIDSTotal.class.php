<?php
/**
 * Inheritance tree class _DAIDSTotal
 *
 */

abstract class _DAIDSTotal extends acCouchdbDocumentTree 
{
	abstract public function getChildrenNode();
	
    public function getProduits() 
    {
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits = array_merge($produits, $item->getProduits());
        }

        return $produits;
    }
    
	public function getConfig() 
	{
        return ConfigurationClient::getCurrent()->get($this->getHash());
    }

    public function getParentNode() 
    {
        return $this->getParent()->getParent();
    }
    
    public function getLieuxArray() 
    {
        $lieux = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $lieux = array_merge($lieux, $item->getLieuxArray());
        }

        return $lieux;
    }

    public function nbComplete() 
    {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbComplete();
        }

        return $nb;
    }

    public function nbToComplete() 
    {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbToComplete();
        }

        return $nb;
    }

    public function isComplete() 
    {
        foreach($this->getChildrenNode() as $item) {
            if(!$item->isComplete()) {
                return false;
            }
        }

        return true;
    }
    
    protected function update($params = array()) 
    {
        parent::update($params);
        if ($this->exist('code') && $this->exist('libelle')) {
        	$this->code = $this->getFormattedCode();
        	$this->libelle = $this->getFormattedLibelle();
        }
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce%") 
    {

      return $this->getConfig()->getLibelleFormat(array(), $format);
    }

   	public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") 
   	{
      return $this->getConfig()->getCodeFormat();
    }
    
    /*public function getDroit($type) 
    {
      return $this->getConfig()->getDroits($this->getInterproKey())->get($type)->getCurrentDroit($this->getPeriode());
    }

    public function getDroits() {
      $conf = $this->getConfig();
      $droits = array();
      foreach ($conf->getDroits($this->getInterproKey()) as $key => $droit) {
	$droits[$key] = $droit->getCurrentDroit($this->getPeriode());
      }
      return $droits;
    }
    public function getInterproKey() 
    {
      if (!$this->getDocument()->getInterpro())
			return array();
      return $this->getDocument()->getInterpro()->get('_id');
    }
    public function getPeriode() 
    {
      return $this->getDocument()->getPeriode();
    }*/
}