<?php
class Vrac extends BaseVrac 
{
    public function constructId() 
    {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);
        if(!$this->date_signature) {
            $this->date_signature = date('d/m/Y');
        }
        if(!$this->date_stats) {
            $this->date_stats = date('d/m/Y');
        }
    }

    public function getProduitObject() 
    {
      return ConfigurationClient::getCurrent()->get($this->produit);
    }
    
    public function getLibelleProduit($format = "%g% %a% %l% %co% %ce%")
    {
    	$produit = $this->getProduitObject();
    	return $produit->getLibelleFormat(array(), $format);
    }

    public function getVendeurObject() 
    {
        return EtablissementClient::getInstance()->find($this->vendeur_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function getAcheteurObject() 
    {
        return EtablissementClient::getInstance()->find($this->acheteur_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function getMandataireObject() 
    {
        return EtablissementClient::getInstance()->find($this->mandataire_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    
    public function getSoussigneObjectById($soussigneId) 
    {
        return EtablissementClient::getInstance()->find($soussigneId,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    public function getVendeurDepartement()
    {
    	if($this->vendeur->code_postal) {
          return substr($this->vendeur->code_postal, 0, 2);
        }

        return null;
    }
    
    public function getVendeurInterpro() 
    {
        return $this->getVendeurObject()->interpro;
    }   
}