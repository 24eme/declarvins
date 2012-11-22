<?php
/**
 * Model for DAIDSDetail
 *
 */

class DAIDSDetail extends BaseDAIDSDetail {
	
	public function renderId()
	{
		return str_replace('/', '_', $this->getHash());
	}
	
	public function getConfig() 
	{
    	return ConfigurationClient::getCurrent()->declaration->certifications->get($this->getCertification()->getKey())->detail;
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") 
    {
    	return $this->getCepage()->getConfig()->getLibelleFormat($this->labels->toArray(), $format, $label_separator);
    }

    public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") 
    {
    	return $this->getCepage()->getConfig()->getCodeFormat($format);
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
		
        $this->stock_chais = $this->stocks->chais + $this->stocks->propriete_tiers;
        $this->stock_propriete = $this->stocks->chais + $this->stocks->tiers;
        $this->total_manquants_excedents = $this->stock_chais - $this->stock_theorique;
        $this->stocks_moyen->vinifie->total = $this->stocks_moyen->vinifie->taux * $this->stocks_moyen->vinifie->volume * 0.01;
        $this->stocks_moyen->non_vinifie->total = $this->stocks_moyen->non_vinifie->taux * $this->stocks_moyen->non_vinifie->volume;
        $this->stocks_moyen->conditionne->total = $this->stocks_moyen->conditionne->taux * $this->stocks_moyen->conditionne->volume;
        $this->total_pertes_autorisees = $this->stocks_moyen->vinifie->total + $this->stocks_moyen->non_vinifie->total + $this->stocks_moyen->conditionne->total;
        $this->total_manquants_taxables = $this->total_pertes_autorisees - $this->total_manquants_excedents;
        $this->total_droits = $this->douane->taux * $this->total_manquants_taxables;
        $this->total_droits_regulation = $this->total_droits - $this->total_regulation;
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
}