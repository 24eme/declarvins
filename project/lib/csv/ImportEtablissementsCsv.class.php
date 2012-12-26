<?php

class ImportEtablissementsCsv {

    protected $_interpro = null;
    protected $_csv = array();

    public function __construct(Interpro $interpro) {
        $file_uri = $interpro->getAttachmentUri("etablissements.csv");
        $this->_interpro = $interpro;
        $this->_csv = array();
    	if (@file_get_contents($file_uri)) {
	        $handler = fopen($file_uri, 'r');
	        if (!$handler) {
	            throw new Exception('Cannot open csv file anymore');
	        }
	        while (($line = fgetcsv($handler, 0, ";")) !== FALSE) {
	            if (preg_match('/[0-9]+/', trim($line[EtablissementCsv::COL_ID]))) {
	                $this->_csv[] = $line;
	            }
	        }
	        fclose($handler);
    	}
    }

    protected function bind($etab, $line) {
    	$etab->identifiant = trim($line[EtablissementCsv::COL_ID]);
        $etab->num_interne = trim($line[EtablissementCsv::COL_NUM_INTERNE]);
        $etab->siret = trim($line[EtablissementCsv::COL_SIRET]);
        $etab->cni = trim($line[EtablissementCsv::COL_CNI]);
        $etab->cvi = trim($line[EtablissementCsv::COL_CVI]);
        $etab->no_accises = trim($line[EtablissementCsv::COL_NO_ASSICES]);
        $etab->no_tva_intracommunautaire = trim($line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE]);
        $etab->famille = EtablissementClient::getInstance()->matchFamille(KeyInflector::slugify(trim($line[EtablissementCsv::COL_FAMILLE])));
        $etab->sous_famille = EtablissementClient::getInstance()->matchSousFamille(trim($line[EtablissementCsv::COL_SOUS_FAMILLE]));
        $etab->nom = trim($line[EtablissementCsv::COL_NOM]);
        $etab->raison_sociale = trim($line[EtablissementCsv::COL_RAISON_SOCIALE]);
        $etab->email = trim($line[EtablissementCsv::COL_EMAIL]);
        $etab->telephone = trim($line[EtablissementCsv::COL_TELEPHONE]);
        $etab->fax = trim($line[EtablissementCsv::COL_FAX]);
        $etab->siege->adresse = trim($line[EtablissementCsv::COL_ADRESSE]);
        $etab->siege->code_postal = trim($line[EtablissementCsv::COL_CODE_POSTAL]);
        $etab->siege->commune = trim($line[EtablissementCsv::COL_COMMUNE]);
        $etab->siege->pays = trim($line[EtablissementCsv::COL_PAYS]);
        $etab->comptabilite->adresse = trim($line[EtablissementCsv::COL_COMPTA_ADRESSE]);
        $etab->comptabilite->code_postal = trim($line[EtablissementCsv::COL_COMPTA_CODE_POSTAL]);
        $etab->comptabilite->commune = trim($line[EtablissementCsv::COL_COMPTA_COMMUNE]);
        $etab->comptabilite->pays = trim($line[EtablissementCsv::COL_COMPTA_PAYS]);
        $etab->service_douane = trim($line[EtablissementCsv::COL_SERVICE_DOUANE]);
		$etab->interpro = trim($line[EtablissementCsv::COL_INTERPRO]);
		$etab->contrat_mandat = 'CONTRAT-'.trim($line[EtablissementCsv::COL_NUMERO_CONTRAT]);
		if (isset($line[EtablissementCsv::COL_CHAMPS_STATUT]) && trim($line[EtablissementCsv::COL_CHAMPS_STATUT])) {
			$etab->statut = trim($line[EtablissementCsv::COL_CHAMPS_STATUT]);
		} else {
			$etab->statut = Etablissement::STATUT_ACTIF;
		}

        return $etab;
    }
    
    public function getEtablissementByIdentifiant($identifiant)
    {
    	$etab = new Etablissement();
    	foreach ($this->_csv as $line) {
    		if (trim($line[EtablissementCsv::COL_ID]) == $identifiant) {
	    		$etab = EtablissementClient::getInstance()->retrieveById(trim($line[EtablissementCsv::COL_ID]));
	            if (!$etab) {
	                $etab = new Etablissement();
	                $etab->set('_id', 'ETABLISSEMENT-' . trim($line[EtablissementCsv::COL_ID]));
	            	$etab->interpro = $this->_interpro->get('_id');
	            }
	            $etab = $this->bind($etab, $line);
	            break;
    		}
    	}
    	return $etab;
    }
    
    
    public function getEtablissementsByContrat(Contrat $contrat)
    {
    	return EtablissementClient::getInstance()->getEtablissementsByContrat($contrat->_id);
    }

    public function update() {
      return $this->updateOrCreate();
    }  

	public function updateOrCreate() 
    {
    	$cpt = 0;
      	foreach ($this->_csv as $line) {
	  		$etab = EtablissementClient::getInstance()->retrieveOrCreateById(trim($line[EtablissementCsv::COL_ID]));
	  		$contrat = (trim($line[EtablissementCsv::COL_NUMERO_CONTRAT]))? ContratClient::getInstance()->retrieveById(trim($line[EtablissementCsv::COL_NUMERO_CONTRAT])) : null;
			if ($etab) {
	  			$etab = $this->bind($etab, $line);
	  			if ($contrat) {
	  				$etab->compte = $contrat->compte;
	  			}
	  			$etab->save();
	  			$this->updateCompte($line, $etab, $contrat);
	  			$cpt++;
			}
      	}
      	return $cpt;
    }  
    
    private function updateCompte($line, $etablissement, $contrat) 
    {
    	if ($contrat) {
	    	$compte = $contrat->getCompteObject();
	    	if (!$compte->interpro->exist(trim($line[EtablissementCsv::COL_INTERPRO]))) {
	    		$interpro = $compte->interpro->add(trim($line[EtablissementCsv::COL_INTERPRO]));
	    		$interpro->statut = _Compte::STATUT_ATTENTE;
	    	}
	    	if (!$compte->tiers->exist($etablissement->get('_id'))) {
	    		$compte->addEtablissement($etablissement);
	    	}
	    	$compte->save();
    	}
    }
}

