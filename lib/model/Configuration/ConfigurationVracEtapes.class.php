<?php
/**
 * Model for ConfigurationVracEtapes
 *
 */

class ConfigurationVracEtapes extends BaseConfigurationVracEtapes 
{
	public function getTabEtapes()
	{
		return $this->toArray();
	}
	
	public function next($current = null) 
	{
		$this->checkEtapes();
		if (!$current) {
			return $this->getFirst();
		}
		if (!in_array($current, array_keys($this->getTabEtapes()))) {
			throw new sfException('Etape inconnu');
		}
		$find = false;
		$etape = null;
		$etapes = $this->getTabEtapes();
		foreach ($etapes as $step => $stepLibelle) {
			if ($find) {
				$etape = $step;
				break;
			}
			if ($current == $step) {
				$find = true;
			}
		}
		return $etape;
	}
	
	public function getFirst()
	{
		$this->checkEtapes();
		$etape = null;
		foreach ($this->getTabEtapes() as $step => $stepLibelle) {
			$etape = $step;
			break;
		}
		return $etape;
	}
	
	public function hasEtapes()
	{
		return (count($this->getTabEtapes()) > 0)? true : false;
	}
	
	public function checkEtapes()
	{
		if (!$this->hasEtapes()) {
			throw new sfException('La configuration vrac ne contient aucune étape');
		}
	}
	
	public function getLibelle($etape)
	{
		$this->checkEtapes();
		$etapes = $this->getTabEtapes();
		if (!isset($etapes[$etape])) {
			throw new sfException('L\'étape "'.$etape.'" n\'existe pas');
		}
		return $etapes[$etape];
	}
	
	public function hasSup($etapeToTest, $etapeReference)
	{
		$this->checkEtapes();
		if (!in_array($etapeToTest, array_keys($this->getTabEtapes()))) {
			throw new sfException('"'.$etapeToTest.'" : étape inconnu');
		}
		if (!in_array($etapeReference, array_keys($this->getTabEtapes()))) {
			throw new sfException('"'.$etapeReference.'" : étape inconnu');
		}
		$findEtapeReference = false;
		$etapes = $this->getTabEtapes();
		foreach ($etapes as $step => $stepLibelle) {
			if ($etapeReference == $step) {
				$findEtapeReference = true;
			}
			if ($etapeToTest == $step) {
				break;
			}
		}
		return $findEtapeReference;
	}
}