<?php
/**
 * Model for DRMCrd
 *
 */

class DRMCrd extends BaseDRMCrd {
	
	public static function makeId($categorie, $type, $centilisation, $centilitre, $bib)
	{
	    $bib = ($bib)? 1 : 0;
		return ($centilitre)? $categorie.'-'.$type.'-'.$centilisation.'-'.$bib.'-CL_'.str_replace(array('.', ','), '_', $centilitre) : $categorie.'-'.$type.'-'.$centilisation;
	}

	public function addCrd($categorie, $type, $centilisation, $centilitre, $bib, $stock = 0)
	{
		$litre = ($centilitre && (intval($centilitre / 100) == $centilitre / 100))? $centilitre / 100 : null;
		$conf = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode());
		$this->categorie->code = $categorie;
		$this->categorie->libelle = $conf->crds->categorie->get($categorie);
		$this->type->code = $type;
		$this->type->libelle = $conf->crds->type->get($type);
		$this->centilisation->code = $centilisation;
		$this->centilisation->libelle = $conf->crds->centilisation->get($centilisation);
		$this->centilisation->centilitre = $centilitre;
		$this->centilisation->bib = $bib;
		if (!$centilitre) {
			$this->libelle = $this->categorie->libelle.' '.$this->type->libelle.' '.$this->centilisation->libelle;
		} elseif ($litre) {
			$this->libelle = ($bib)? $this->categorie->libelle.' '.$this->type->libelle.' BIB '.$litre.' L' : $this->categorie->libelle.' '.$this->type->libelle.' '.$litre.' L';
		} else {
			$this->libelle = ($bib)? $this->categorie->libelle.' '.$this->type->libelle.' BIB '.$centilitre.' cL' : $this->categorie->libelle.' '.$this->type->libelle.' '.$centilitre.' cL';
		}
		$this->total_debut_mois = ($stock > 0)? $stock : 0;
		$this->total_fin_mois = ($stock > 0)? $stock : 0;
	}
	
	public function updateStocks()
	{
		$stockEntrees = 0;
		$stockSorties = 0;
		foreach ($this->entrees as $stock) {
			$stockEntrees += $stock;
		}
		foreach ($this->sorties as $stock) {
			$stockSorties += $stock;
		}
		$this->total_fin_mois = $this->total_debut_mois + $stockEntrees - $stockSorties;
	}
	
	public function getTotalEntrees()
	{
		$total = 0;
		foreach ($this->entrees as $stock) {
			$total += $stock;
		}
		return $total;
	}
	
	public function getTotalSorties()
	{
		$total = 0;
		foreach ($this->sorties as $stock) {
			$total += $stock;
		}
		return $total;
	}
	
	public function initCrd()
	{
		$this->total_debut_mois = $this->total_fin_mois;
		foreach ($this->entrees as $entree => $stock) {
			$this->entrees->set($entree, null);
		}
		foreach ($this->sorties as $sortie => $stock) {
			$this->sorties->set($sortie, null);
		}
	}
}