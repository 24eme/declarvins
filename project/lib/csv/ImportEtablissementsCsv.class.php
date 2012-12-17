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
	            if (preg_match('/[0-9]+/', $line[EtablissementCsv::COL_ID])) {
	                $this->_csv[] = $line;
	            }
	        }
	        fclose($handler);
    	}
    }

    protected function bind($etab, $line) {
    	$etab->identifiant = $line[EtablissementCsv::COL_ID];
        $etab->num_interne = $line[EtablissementCsv::COL_NUM_INTERNE];
        $etab->siret = $line[EtablissementCsv::COL_SIRET];
        $etab->cni = $line[EtablissementCsv::COL_CNI];
        $etab->cvi = $line[EtablissementCsv::COL_CVI];
        $etab->no_accises = $line[EtablissementCsv::COL_NO_ASSICES];
        $etab->no_tva_intracommunautaire = $line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE];
        $etab->famille = EtablissementClient::getInstance()->matchFamille(KeyInflector::slugify($line[EtablissementCsv::COL_FAMILLE]));
        $etab->sous_famille = EtablissementClient::getInstance()->matchSousFamille($line[EtablissementCsv::COL_SOUS_FAMILLE]);
        $etab->nom = $line[EtablissementCsv::COL_NOM];
        $etab->raison_sociale = $line[EtablissementCsv::COL_RAISON_SOCIALE];
        $etab->email = $line[EtablissementCsv::COL_EMAIL];
        $etab->telephone = $line[EtablissementCsv::COL_TELEPHONE];
        $etab->fax = $line[EtablissementCsv::COL_FAX];
        $etab->siege->adresse = $line[EtablissementCsv::COL_ADRESSE];
        $etab->siege->code_postal = $line[EtablissementCsv::COL_CODE_POSTAL];
        $etab->siege->commune = $line[EtablissementCsv::COL_COMMUNE];
        $etab->siege->pays = $line[EtablissementCsv::COL_PAYS];
        $etab->comptabilite->adresse = $line[EtablissementCsv::COL_COMPTA_ADRESSE];
        $etab->comptabilite->code_postal = $line[EtablissementCsv::COL_COMPTA_CODE_POSTAL];
        $etab->comptabilite->commune = $line[EtablissementCsv::COL_COMPTA_CODE_POSTAL];
        $etab->comptabilite->pays = $line[EtablissementCsv::COL_COMPTA_PAYS];
        $etab->service_douane = $line[EtablissementCsv::COL_SERVICE_DOUANE];
		$etab->interpro = $line[EtablissementCsv::COL_INTERPRO];

        return $etab;
    }
    
    public function getEtablissementByIdentifiant($identifiant)
    {
    	$etab = new Etablissement();
    	foreach ($this->_csv as $line) {
    		if ($line[EtablissementCsv::COL_ID] == $identifiant) {
	    		$etab = EtablissementClient::getInstance()->retrieveById($line[EtablissementCsv::COL_ID]);
	            if (!$etab) {
	                $etab = new Etablissement();
	                $etab->set('_id', 'ETABLISSEMENT-' . $line[EtablissementCsv::COL_ID]);
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
    	$etablissements = array();
    	foreach ($this->_csv as $line) {
    		if ($line[EtablissementCsv::COL_NUMERO_CONTRAT] == $contrat->no_contrat) {
    			$etab = EtablissementClient::getInstance()->retrieveById($line[EtablissementCsv::COL_ID]);
	            if (!$etab) {
	                $etab = new Etablissement();
	                $etab->set('_id', 'ETABLISSEMENT-' . $line[EtablissementCsv::COL_ID]);
	            	$etab->interpro = $this->_interpro->get('_id');
		            $etab = $this->bind($etab, $line);
		            $etab->statut = Etablissement::STATUT_CSV;
	            }
	            $etablissements[$etab->get('_id')] = $etab;
    		}
    	}
    	return $etablissements;
    }

    public function update() {
      return $this->updateOrCreate(true);
    }  

	public function updateOrCreate($dontcreate = false) 
    {
    	$cpt = 0;
      	foreach ($this->_csv as $line) {
			if (!$dontcreate)
	  			$etab = EtablissementClient::getInstance()->retrieveOrCreateById($line[EtablissementCsv::COL_ID]);
			else
	  			$etab = EtablissementClient::getInstance()->retrieveById($line[EtablissementCsv::COL_ID]);
			if ($etab) {
	  			$etab = $this->bind($etab, $line);
	  			$etab->save();
	  			$this->updateCompte($line);
	  			$cpt++;
			} else {
				echo "probeme";exit;
			}
      	}
      	return $cpt;
    }  
    
    private function updateCompte($line) 
    {
    	if ($line[EtablissementCsv::COL_NUMERO_CONTRAT]) {
	    	$contrat = ContratClient::getInstance()->retrieveById($line[EtablissementCsv::COL_NUMERO_CONTRAT]);
	    	if ($contrat) {
		    	$compte = $contrat->getCompteObject();
		    	if (!$compte->interpro->exist($line[EtablissementCsv::COL_INTERPRO])) {
		    		$interpro = $compte->interpro->add($line[EtablissementCsv::COL_INTERPRO]);
		    		$interpro->statut = _Compte::STATUT_VALIDATION_ATTENTE;
		    		$compte->save();
		    	}else {
		    		echo "chelou3";exit;
		    	}
	    	}else {
	    		echo get_class($contrat);exit;
	    	}
    	} else {
    		echo "chelou";exit;
    	}
    }
}

