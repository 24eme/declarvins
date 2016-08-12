<?php
/**
 * Model for DRMCiel
 *
 */

class DRMCiel extends BaseDRMCiel 
{
	public function setInformationsFromXml()
	{

		$this->transfere = 0;
		$this->identifiant_declaration = null;
		$this->horodatage_depot = null;
		if ($reponseCiel = $this->getReponseCiel()) {
			if (isset($reponseCiel->{'identifiant-declaration'}) && isset($reponseCiel->{'horodatage-depot'})) {
				$this->transfere = 1;
				$this->identifiant_declaration = $reponseCiel->{'identifiant-declaration'};
				$this->horodatage_depot = $reponseCiel->{'horodatage-depot'};
			}
		}
	}
	
	public function getReponseCiel()
	{
		return ($this->xml)? new SimpleXMLElement($this->xml) : null;
	}
	
	public function isTransfere()
	{
		return ($this->transfere && $this->identifiant_declaration && $this->horodatage_depot)? true : false;
	}
	
	public function hasErreurs()
	{
		return (count($this->getErreurs()) > 0);
	}
	
	public function getErreurs()
	{
		$erreurs = array();
		if ($this->xml && !$this->isTransfere()) {
			$reponseCiel = $this->getReponseCiel();
			if (isset($reponseCiel->{'erreurs-fonctionnelles'})) {
				foreach ($reponseCiel->{'erreurs-fonctionnelles'}->{'erreur-fonctionnelle'} as $erreurFonctionnelle) {
					if (isset($erreurFonctionnelle->{'message-erreur'})) {
						$erreurs[] =  $erreurFonctionnelle->{'message-erreur'};
					}
				}
			}
			if (isset($reponseCiel->{'erreur-technique'})) {
				if (isset($reponseCiel->{'erreur-technique'}->{'message-erreur'})) {
					$erreurs[] =  $reponseCiel->{'erreur-technique'}->{'message-erreur'};
				}
			}
			if (isset($reponseCiel->{'erreur-interne'})) {
				if (isset($reponseCiel->{'erreur-interne'}->{'message-erreur'})) {
					$erreurs[] =  $reponseCiel->{'erreur-interne'}->{'message-erreur'};
				}
			}
		}
		return $erreurs;
	}

}