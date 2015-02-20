<?php
/**
 * Model for DAIDSDetail
 *
 */

class DAIDSDetail extends BaseDAIDSDetail {
	
	public function renderId()
	{
		return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
	}
	
	public function getConfig() 
    {
    	return ConfigurationClient::getCurrent()->getConfigurationProduit($this->getCepage()->getHash());
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") 
    {
    	return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getLibelles(), $this->labels->toArray(), $format);
    }

    public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") 
    {
    	return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getCodes(), array(), $format);
    }
    
    public function getCepage() 
    {
        return $this->getParent()->getParent();
    }
    
    public function getCouleur() 
    {
        return $this->getCepage()->getCouleur();
    }
    
    public function getLieu() 
    {
        return $this->getCouleur()->getLieu();
    }
    
    public function getMention() 
    {
        return $this->getLieu()->getMention();
    }
    
    public function getAppellation() 
    {
        return $this->getLieu()->getAppellation();
    }

    public function getGenre() 
    {
      return $this->getAppellation()->getGenre();
    }

    public function getCertification() 
    {
      return $this->getGenre()->getCertification();
    }
    
    public function getLabelKeyString() 
    {
      	if ($this->labels) {
			return implode('|', $this->labels->toArray());
      	}
      	return '';
    }

    public function getLabelKey() 
    {
    	$key = null;
    	if ($this->labels) {
    		$key = implode('-', $this->labels->toArray());
    	}
    	return ($key) ? $key : DRM::DEFAULT_KEY;
    }

	public function getLabelsLibelle($format = "%la%", $separator = ", ") 
	{
      	return str_replace("%la%", implode($separator, $this->libelles_label->toArray()), $format);
    } 
    
	protected function update($params = array()) 
	{
        parent::update($params);
        $this->code = $this->getFormattedCode();
        $this->libelle = $this->getFormattedLibelle("%g% %a% %l% %co% %ce%");
		$this->selecteur = 1;
        $this->stock_chais = $this->stocks->chais + $this->stocks->propriete_tiers;
        $this->stock_propriete = $this->stocks->chais + $this->stocks->tiers;
        $this->total_manquants_excedents = $this->stock_chais - $this->stock_theorique;
        $this->stock_propriete_details->vrac_libre = $this->stock_propriete - $this->stock_propriete_details->reserve - $this->stock_propriete_details->vrac_vendu - $this->stock_propriete_details->conditionne;
        $this->stocks_moyen->vinifie->total = $this->stocks_moyen->vinifie->taux * $this->stocks_moyen->vinifie->volume * 0.01;
        //$this->stocks_moyen->non_vinifie->volume = $this->stock_mensuel_theorique - $this->stocks_moyen->vinifie->volume;
        $this->stocks_moyen->conditionne->total = $this->stocks_moyen->conditionne->taux * $this->stocks_moyen->conditionne->volume;
        $this->total_pertes_autorisees = $this->stocks_moyen->vinifie->total + $this->stocks_moyen->non_vinifie->total + $this->stocks_moyen->conditionne->total;
        $this->total_manquants_taxables = (-1 * $this->total_manquants_excedents) - $this->total_pertes_autorisees;
        if ($this->total_manquants_taxables < 0) {
        	$this->total_manquants_taxables = 0;
        }
        $this->total_douane = $this->douane->taux * $this->total_manquants_taxables;
        if ($this->total_douane < 0) {
        	$this->total_douane = 0;
        }
        if (!isset($params['cvo_manuel']) || !$params['cvo_manuel']) {
        	$this->total_manquants_taxables_cvo = $this->total_manquants_taxables;
        }
        $this->total_cvo = $this->cvo->taux * $this->total_manquants_taxables_cvo;
        if ($this->total_cvo < 0) {
        	$this->total_cvo = 0;
        }
    }
    
    public function updateCvo($params)
    {
    	$this->update($params);
    }
    
    public function getCvoCalcul()
    {
    	return round(($this->cvo->taux * $this->total_manquants_taxables_cvo),2);
    }
    
    public function isUpdatedCvo()
    {
    	return ($this->total_manquants_taxables == $this->total_manquants_taxables_cvo)? false : true;
    }

    public function nbToComplete() {
    	return 1;
    }

    public function nbComplete() {
    	return $this->isComplete();
    }
    
    public function isComplete() {
        return $this->stocks->chais > 0 || $this->stocks->propriete_tiers > 0 || $this->stocks->tiers > 0;
    }
    
    public function updateVolumeBloque()
    {
    	$produitHash =  str_replace('/declaration/', '', $this->getCepage()->getHash());
      	$produitHash = str_replace('/', '_', $produitHash);
      	$etablissement = $this->getDocument()->getEtablissement();
      	$date = $this->getDocument()->periode.'-01'; 
      	if ($etablissement->produits->exist($produitHash)) {
      		$this->stocks_debut->bloque = $etablissement->getVolumeBloque($produitHash, $date);
      	}
    }
    


    public function cascadingDelete() {
        $cepage = $this->getCepage();
        $couleur = $this->getCouleur();
        $lieu = $this->getLieu();
        $mention = $this->getMention();
        $appellation = $this->getAppellation();
        $genre = $this->getGenre();
        $certification = $this->getCertification();
        $objectToDelete = $this;
        if ($cepage->details->count() == 1 && $cepage->details->exist($this->getKey())) {
            $objectToDelete = $cepage;
            if ($couleur->cepages->count() == 1 && $couleur->cepages->exist($cepage->getKey())) {
                $objectToDelete = $couleur;
                if ($lieu->couleurs->count() == 1 && $lieu->couleurs->exist($couleur->getKey())) {
                    $objectToDelete = $lieu;
                    if ($mention->lieux->count() == 1 && $mention->lieux->exist($lieu->getKey())) {
                        $objectToDelete = $mention;
                        if ($appellation->mentions->count() == 1 && $appellation->mentions->exist($mention->getKey())) {
                            $objectToDelete = $appellation;
                            if ($genre->appellations->count() == 1 && $genre->appellations->exist($appellation->getKey())) {
                                $objectToDelete = $genre;
                                if ($certification->genres->count() == 1 && $certification->genres->exist($genre->getKey())) {
                                    $objectToDelete = $certification;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $objectToDelete;
    }
}