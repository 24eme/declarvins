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
}