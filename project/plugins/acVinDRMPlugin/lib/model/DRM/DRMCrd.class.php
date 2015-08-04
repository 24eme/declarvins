<?php
/**
 * Model for DRMCrd
 *
 */

class DRMCrd extends BaseDRMCrd {
	
	public static function makeId($categorie, $type, $centilisation)
	{
		return $categorie.'-'.$type.'-'.$centilisation;
	}
	


	public function addCrd($categorie, $type, $centilisation)
	{
		$conf = ConfigurationClient::getCurrent();
		$this->categorie->code = $categorie;
		$this->categorie->libelle = $conf->crds->categorie->get($categorie);
		$this->type->code = $type;
		$this->type->libelle = $conf->crds->type->get($type);
		$this->centilisation->code = $centilisation;
		$this->centilisation->libelle = $conf->crds->centilisation->get($centilisation);
		$this->libelle = $this->categorie->libelle.' '.$this->type->libelle.' '.$this->centilisation->libelle;
		$this->total_debut_mois = 0;
		$this->total_fin_mois = 0;
	}

}