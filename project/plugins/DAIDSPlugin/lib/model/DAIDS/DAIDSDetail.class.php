<?php
/**
 * Model for DAIDSDetail
 *
 */

class DAIDSDetail extends BaseDAIDSDetail {
	
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
	
        $labelLibelles = $this->getConfig()->getDocument()->getLabelsLibelles($this->labels->toArray());
        foreach ($labelLibelles as $label => $libelle) {
        	$this->libelles_label->add($label, $libelle);
        }
    }

    public function nbToComplete() {
    	return 0; // A FAIRE
    }

    public function nbComplete() {
    	return $this->isComplete();
    }
    
    public function isComplete() {
        return 0; // A FAIRE
    }
}