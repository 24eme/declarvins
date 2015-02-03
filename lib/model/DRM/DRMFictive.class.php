<?php

class DRMFictive extends DRM 
{
	protected $drm;
	protected $interpro;
	protected $configurationProduits;
	
    public function __construct($drm, $interpro) 
    {
        parent::__construct();
    	$this->drm = $drm;
    	$this->interpro = $interpro;
    	$this->configurationProduits = ConfigurationProduitClient::getInstance()->find($interpro->getOrAdd('configuration_produits'));
    	$this->initDrm();
    	$this->initProduits();
    	
    }
    
    protected function initDrm()
    {
        $datas = $this->drm->getData();
        $this->loadFromCouchdb($datas);
    }
    
    protected function initProduits()
    {
    	$produits = $this->getDetails();
    	$hasChange = false;
    	foreach ($produits as $produit) {
    		if ($produit->interpro != $this->interpro->_id) {
    			if (!$this->configurationProduits->isProduitInPrestation($produit->getCepage()->getHash())) {
    				$this->remove($produit->getHash());
    				$hasChange = true;
    			}
    		}
    	}
    	if ($hasChange) {
			$this->update();
    	}
    }
    
    /**
     * 
     * @todo
     */
    public function save() 
    {
    	
    }
    public function delete() 
    {
    	
    }

}