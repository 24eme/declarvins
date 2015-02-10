<?php

class DAIDSFictive extends DAIDS 
{
	protected $daids;
	protected $interpro;
	protected $configurationProduits;
	const TYPE = 'DAIDSFictive';
	
    public function __construct($daids, $interpro) 
    {
        parent::__construct();
    	$this->daids = $daids;
    	$this->interpro = $interpro;
    	$this->configurationProduits = ConfigurationProduitClient::getInstance()->find($interpro->getOrAdd('configuration_produits'));
    	$this->initDaids();
    	$this->initAccessibleProduits();
    	$this->type = self::TYPE;
    	
    }
    
    public function getDAIDS()
    {
    	return $this->daids;
    }
    
    protected function initDaids()
    {
        $datas = $this->daids->getData();
        $this->loadFromCouchdb($datas);
    }
    
    protected function initAccessibleProduits()
    {
    	$produits = $this->getDetails();
    	$hasChange = false;
    	foreach ($produits as $produit) {
    		if ($produit->interpro != $this->interpro->_id) {
    			if (!$this->configurationProduits->isProduitInPrestation($produit->getCepage()->getHash())) {
    				$object = $produit->cascadingDelete();
    				$this->remove($object->getHash());
    				$hasChange = true;
    			}
    		}
    	}
    	if ($hasChange) {
			$this->update();
    	}
    	$this->setDroits();
    }
    
    public function save() 
    {
    	$daids = $this->daids;
    	$produits = $this->getDetails();
    	foreach ($produits as $produit) {
    		$detail = $daids->getOrAdd($produit->getHash());
    		$detail->getParent()->set($produit->getKey(), $produit);
    	}
        $daids->update();
    	$daids->save();
    	
    }
    public function delete() 
    {
    	$this->daids->delete();
    }
    
    public function validate($options = array()) 
    {
    	$this->daids->validate($options);
    }
    
    public function setCurrentEtapeRouting($etape) 
    {
    	$this->daids->setCurrentEtapeRouting($etape);
    }
    
    public function setEtablissementInformations($etablissement = null) 
    {
    	$this->daids->setEtablissementInformations($etablissement);
    }
    
    public function setEntrepotsInformations($entrepots = array())
    {
    	$this->daids->setEntrepotsInformations($entrepots);
    }

}