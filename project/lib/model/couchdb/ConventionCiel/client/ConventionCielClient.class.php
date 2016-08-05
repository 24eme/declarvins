<?php

class ConventionCielClient extends acCouchdbClient 
{
    public static function getInstance()
    {
      return acCouchdbManager::getClient("ConventionCiel");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Contrat
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
    	return parent::find('CONVENTIONCIEL-'.$id, $hydrate);
    }
    
    public function createObject($compte)
    {
    	$convention = new ConventionCiel();
    	$convention->set('_id', 'CONVENTIONCIEL-'.$compte->login);
    	$convention->no_convention = $compte->login;
    	$convention->compte = $compte->_id;
    	$convention->nom = $compte->nom;
    	$convention->prenom = $compte->prenom;
    	$convention->fonction = $compte->fonction;
    	$convention->email = $compte->email;
    	$convention->telephone = $compte->telephone;
    	$convention->valide = 0;
    	$interpro = array();
    	$nbEtablissement = 0;
    	$currentEtab = null;
    	foreach ($compte->getTiersCollection() as $etablissement) {
    		if (!$etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
    			continue;
    		}
    		$nbEtablissement++;
    		$currentEtab = $etablissement;
    		$etab = $convention->etablissements->getOrAdd($etablissement->_id);
    		$etab->nom = $etablissement->nom;
    		$etab->raison_sociale = $etablissement->raison_sociale;
    		$etab->siret = $etablissement->siret;
    		$etab->cni = $etablissement->cni;
    		$etab->cvi = $etablissement->cvi;
    		$etab->siege->adresse = $etablissement->siege->adresse;
    		$etab->siege->code_postal = $etablissement->siege->code_postal;
    		$etab->siege->commune = $etablissement->siege->commune;
    		$etab->siege->pays = $etablissement->siege->pays;
    		$etab->comptabilite->adresse = $etablissement->comptabilite->adresse;
    		$etab->comptabilite->code_postal = $etablissement->comptabilite->code_postal;
    		$etab->comptabilite->commune = $etablissement->comptabilite->commune;
    		$etab->comptabilite->pays = $etablissement->comptabilite->pays;
    		$etab->no_accises = $etablissement->no_accises;
    		$etab->no_tva_intracommunautaire = $etablissement->no_tva_intracommunautaire;
    		$etab->email = $etablissement->email;
    		$etab->telephone = $etablissement->telephone;
    		$etab->fax = $etablissement->fax;
    		$etab->famille = $etablissement->famille;
    		$etab->sous_famille = $etablissement->sous_famille;
    		$etab->service_douane = $etablissement->service_douane;
    		$interpro[$etablissement->interpro] = $etablissement->interpro; 
    	}
    	if (count($interpro) == 1) {
    		$convention->interpro = current($interpro);
    	}
    	if ($nbEtablissement == 1) {
    		$convention->raison_sociale = ($currentEtab->raison_sociale)? $currentEtab->raison_sociale : $currentEtab->nom ;
    		$convention->no_operateur = $currentEtab->siret;
    	}
    	return $convention;
    }
}
