<?php

class ImportEtablissementsCsv {

    protected $_interpro = null;
    protected $_csv = array();
    protected $_errors = array();

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
    
    public function getErrors() {
    	return $this->_errors;
    }
    
    protected function existLine($ligne, $line)
    {
    	$errors = array();
   		if (!isset($line[EtablissementCsv::COL_ID])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_ID + 1).') "id" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NUM_INTERNE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NUM_INTERNE + 1).') "numero interne" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_SIRET])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_SIRET + 1).') "siret" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CNI])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CNI + 1).') "cni" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CVI])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CVI + 1).') "cvi" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NO_ASSICES])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NO_ASSICES + 1).') "numéro assices" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE + 1).') "numéro tva intracommunautaire" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_FAMILLE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_FAMILLE + 1).') "famille" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_SOUS_FAMILLE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_SOUS_FAMILLE + 1).') "sous famille" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NOM])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NOM + 1).') "nom commercial" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_RAISON_SOCIALE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_RAISON_SOCIALE + 1).') "raison sociale" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_EMAIL])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_EMAIL + 1).') "email" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_TELEPHONE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_TELEPHONE + 1).') "telephone" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_FAX])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_FAX + 1).') "fax" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_ADRESSE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_ADRESSE + 1).') "adresse" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CODE_POSTAL])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CODE_POSTAL + 1).') "code postal" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_COMMUNE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_COMMUNE + 1).') "commune" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_PAYS])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_PAYS + 1).') "pays" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_COMPTA_ADRESSE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_COMPTA_ADRESSE + 1).') "compta. adresse" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_COMPTA_CODE_POSTAL])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_COMPTA_CODE_POSTAL + 1).') "compta. code postal" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_COMPTA_COMMUNE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_COMPTA_COMMUNE + 1).') "compta. commune" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_COMPTA_PAYS])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_COMPTA_PAYS + 1).') "compta. pays" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_SERVICE_DOUANE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_SERVICE_DOUANE + 1).') "service douane" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_INTERPRO])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_INTERPRO + 1).') "interpro" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NUMERO_CONTRAT])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NUMERO_CONTRAT + 1).') "numero contrat" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_STATUT])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_STATUT + 1).') "statut" manquante');
   		}
   		if (count($errors) > 0) {
   			$this->_errors[$ligne] = $errors;
   			throw new sfException('has errors');
   		}
    }

    protected function bind($ligne, $etab, $line) {
    	$this->existLine($ligne, $line);
    	$etab->identifiant = trim($line[EtablissementCsv::COL_ID]);
        $etab->num_interne = trim($line[EtablissementCsv::COL_NUM_INTERNE]);
        $etab->siret = trim($line[EtablissementCsv::COL_SIRET]);
        $etab->cni = trim($line[EtablissementCsv::COL_CNI]);
        $etab->cvi = trim($line[EtablissementCsv::COL_CVI]);
        $etab->no_accises = trim($line[EtablissementCsv::COL_NO_ASSICES]);
        $etab->no_tva_intracommunautaire = trim($line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE]);
        try {
        	$etab->famille = EtablissementClient::getInstance()->matchFamille(KeyInflector::slugify(trim($line[EtablissementCsv::COL_FAMILLE])));
        	$etab->sous_famille = EtablissementClient::getInstance()->matchSousFamille(trim($line[EtablissementCsv::COL_SOUS_FAMILLE]));
        } catch (sfException $e) {
        	if (isset($this->_errors[$ligne])) {
        		$merge = $this->_errors[$ligne];
        		$merge[] = $e->getMessage();
        		$this->_errors[$ligne] = $merge;
        	} else {
        		$this->_errors[$ligne] = array($e->getMessage());
        	}
        	throw new sfException('has errors');
        }
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
    	$ligne = 1;
    	foreach ($this->_csv as $line) {
      		$ligne++;
    		if (trim($line[EtablissementCsv::COL_ID]) == $identifiant) {
	    		$etab = EtablissementClient::getInstance()->retrieveById(trim($line[EtablissementCsv::COL_ID]));
	            if (!$etab) {
	                $etab = new Etablissement();
	                $etab->set('_id', 'ETABLISSEMENT-' . trim($line[EtablissementCsv::COL_ID]));
	            	$etab->interpro = $this->_interpro->get('_id');
	            }
	            $etab = $this->bind($ligne, $etab, $line);
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
    	$ligne = 1;
      	foreach ($this->_csv as $line) {
      		$ligne++;
	  		$etab = EtablissementClient::getInstance()->retrieveOrCreateById(trim($line[EtablissementCsv::COL_ID]));
	  		$contrat = (trim($line[EtablissementCsv::COL_NUMERO_CONTRAT]))? ContratClient::getInstance()->retrieveById(trim($line[EtablissementCsv::COL_NUMERO_CONTRAT])) : null;
			if ($etab) {
				try {
  					$etab = $this->bind($ligne, $etab, $line);
		  			if ($contrat) {
		  				$etab->compte = $contrat->compte;
		  			}
		  			$etab->save();
		  			$this->updateCompte($line, $etab, $contrat);
		  			$cpt++;
				} catch (sfException $e) {
					continue;
				}
			}
      	}
      	if (count($this->_errors) > 0) {
      		throw new sfException("has errors");
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

