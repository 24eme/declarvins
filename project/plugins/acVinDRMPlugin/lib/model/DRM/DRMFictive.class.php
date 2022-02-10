<?php

class DRMFictive extends DRM 
{
	protected $drm;
	protected $interpro;
	protected $configurationProduits;
	const TYPE = 'DRMFictive';
	
    public function __construct($drm, $interpro) 
    {
        parent::__construct();
    	$this->drm = $drm;
    	$this->interpro = $interpro;
    	$this->configurationProduits = ConfigurationProduitClient::getInstance()->getByInterpro($interpro->identifiant, $this->drm->getDateDebutPeriode());
    	$this->initDrm();
    	$this->initProduits();
    	$this->type = self::TYPE;
    	
    }
    
    public function setDRM($drm)
    {
    	$this->drm = $drm;
    }
    
    public function getDRM()
    {
    	return $this->drm;
    }
    
    protected function initDrm()
    {
        $datas = $this->drm->getData();
        $this->loadFromCouchdb($datas);
    }
    
    protected function initProduits()
    {
    	$produits = $this->getDetails();
    	foreach ($produits as $produit) {
    		if ($produit->interpro != $this->interpro->_id) {
    			if (!$this->configurationProduits->isProduitInPrestation($produit->getCepage()->getHash())) {
    				$object = $produit->cascadingFictiveDelete();
    				$this->remove($object->getHash());
    			}
    		}
    	}
    }

    protected function preSave() {
    	return;
    }
    public function save()
    {
    	$drm = $this->drm;
    	$produits = $this->getDetails();
    	foreach ($produits as $produit) {
    		$detail = $drm->getOrAdd($produit->getHash());
    		$detail->getParent()->set($produit->getKey(), $produit);
    	}
        $drm->update();
        $drm->remove('crds');
        $drm->add('crds');
        $drm->crds = $this->crds;
    	$drm->save();

    }
    public function delete() 
    {
    	$this->drm->delete();
    }
    
    public function validate($options = array()) 
    {
    	$this->drm->validate($options);
    }
    
    public function setCurrentEtapeRouting($etape) 
    {
    	$this->drm->setCurrentEtapeRouting($etape);
    }
    
    public function setEtablissementInformations($etablissement = null) 
    {
    	$this->drm->setEtablissementInformations($etablissement);
    }
    
	public function generateRectificative($force = false) {
        return $this->drm->generateRectificative($force);
    }

    public function generateModificative() {
        return $this->drm->generateModificative();
    }
    

    public function addCrd($categorie, $type, $centilisation, $centilitre, $bib, $stock = 0)
    {
    	return $this->drm->addCrd($categorie, $type, $centilisation, $centilitre, $bib, $stock);
    }
    

    public function setHasDroitsAcquittes($has = 0)
    {
    	return $this->drm->setHasDroitsAcquittes($has);
    }
    
    public function payerReport()
    {
    	$this->drm->payerReport();
    }
    
    public function addObservationProduit($hash, $observation)
    {
    	$this->drm->addObservationProduit($hash, $observation);
    }
    
    public function isFictive()
    {
    	return true;
    }

}