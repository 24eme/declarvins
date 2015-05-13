<?php

class EtablissementAllView extends acCouchdbView
{
	const KEY_STATUT_FILTRE = 0;
	const KEY_ZONE_ID = 1;
	const KEY_FAMILLE = 2;
	const KEY_SOUS_FAMILLE = 3;
	const KEY_SOCIETE = 4;
	const KEY_ETABLISSEMENT_ID = 5;
	const KEY_NOM = 6;
	const KEY_IDENTIFIANT = 7;
	const KEY_RAISON_SOCIALE = 8;
	const KEY_SIRET = 9;
	const KEY_CVI = 10;
	const KEY_COMMUNE = 11;
	const KEY_CODE_POSTAL = 12;
	const KEY_PAYS = 13;
	const KEY_STATUT = 14;
	const KEY_CONTRAT = 15;
	const KEY_DOUANE = 16;

	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'all', 'Etablissement');
    }

    public function findByZone($zone) {

    	return $this->client->startkey(array(Etablissement::STATUT_ACTIF, $zone))
                    		->endkey(array(Etablissement::STATUT_ACTIF, $zone, array()))
                    		->getView($this->design, $this->view);
    }

    public function findAllByZone($zone) {
    	$actifs = $this->client->startkey(array(Etablissement::STATUT_ACTIF, $zone))
                    		->endkey(array(Etablissement::STATUT_ACTIF, $zone, array()))
                    		->getView($this->design, $this->view)->rows;
        $archives = $this->client->startkey(array(Etablissement::STATUT_ARCHIVE, $zone))
                    		->endkey(array(Etablissement::STATUT_ARCHIVE, $zone, array()))
                    		->getView($this->design, $this->view)->rows;
		return array_merge($actifs, $archives);
    }

    public function findByZoneAndFamilles($zone, array $familles) {
    	$etablissements = array();
    	foreach($familles as $famille) {
    		$etablissements = array_merge($etablissements, $this->findByZoneAndFamille($zone, $famille)->rows);
    	}
    	return $etablissements;
    }

    public function findByZoneAndSousFamilles($zone, $sous_familles) {
    	$etablissements = array();
    	foreach($sous_familles as $famille => $sous_famille) {
    		if ($sous_famille)
    			$etablissements = array_merge($etablissements, $this->findByZoneAndSousFamille($zone, $famille, $sous_famille)->rows);
    		else 
    			$etablissements = array_merge($etablissements, $this->findByZoneAndSousFamille($zone, $famille)->rows);
    	}
    	return $etablissements;
    }

    public function findByZoneAndFamille($zone, $famille) {
    	return $this->client->startkey(array(Etablissement::STATUT_ACTIF, $zone, $famille))
                    		->endkey(array(Etablissement::STATUT_ACTIF, $zone, $famille, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByZoneAndSousFamille($zone, $famille, $sous_famille) {

    	return $this->client->startkey(array(Etablissement::STATUT_ACTIF, $zone, $famille, $sous_famille))
                    		->endkey(array(Etablissement::STATUT_ACTIF, $zone, $famille, $sous_famille, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByContrat($contrat) {
		$etablissements = $this->client->getView($this->design, $this->view)->rows;
		$result = array();
		foreach ($etablissements as $etablissement) {
			if ($etablissement->key[self::KEY_CONTRAT] == $contrat && !in_array($etablissement->key[self::KEY_IDENTIFIANT], array_keys($result))) {
				$result[$etablissement->key[self::KEY_IDENTIFIANT]] = $etablissement;
			}
		}
		return $result;
    }

    public function findByDouane($douane) {
		$etablissements = $this->client->getView($this->design, $this->view)->rows;
		$result = array();
		foreach ($etablissements as $etablissement) {
			if ($etablissement->key[self::KEY_DOUANE] == $douane && !in_array($etablissement->key[self::KEY_IDENTIFIANT], array_keys($result))) {
				$result[$etablissement->key[self::KEY_IDENTIFIANT]] = $etablissement->key;
			}
		}
		return $result;
    }

    public static function makeLibelle($datas) {
        $libelle = '';

        if ($nom = $datas[self::KEY_NOM]) {
            $libelle .= $nom;
        }

        if (isset($datas[self::KEY_RAISON_SOCIALE]) && $rs = $datas[self::KEY_RAISON_SOCIALE]) {
            if ($libelle) {
                $libelle .= ' / ';
            }
            $libelle .= $rs;
        }

        $libelle .= ' ('.$datas[self::KEY_IDENTIFIANT];
        if (isset($datas[self::KEY_SIRET]) && $siret = $datas[self::KEY_SIRET]) {
            $libelle .= ' / '.$siret;
        }

        if (isset($datas[self::KEY_CVI]) && $cvi = $datas[self::KEY_CVI]) {
            $libelle .= ' / '.$cvi;
        }
        $libelle .= ') ';

    	if (isset($datas[self::KEY_FAMILLE]))
    	  	$libelle .= $datas[self::KEY_FAMILLE];

    	if (isset($datas[self::KEY_COMMUNE]))
    	  	$libelle .= ' '.$datas[self::KEY_COMMUNE];

    	if (isset($datas[$datas[self::KEY_CODE_POSTAL]]))
    	  	$libelle .= ' '.$datas[$datas[self::KEY_CODE_POSTAL]];

    	if (isset($datas[$datas[self::KEY_PAYS]]))
    	  	$libelle .= ' '.$datas[$datas[self::KEY_PAYS]];

    	if (isset($datas[self::KEY_STATUT]))
    	  	$libelle .= ' / '.$datas[self::KEY_STATUT];
        
        return trim($libelle);
    }

}  