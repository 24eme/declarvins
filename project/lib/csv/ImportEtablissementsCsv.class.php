<?php

class ImportEtablissementsCsv {

    protected $_interpro = null;
    protected $_csv = array();
    protected $_errors = array();
    protected $_interpros = array();
    protected $_zones = array();
    protected $_zoneClient = null;
    protected $_zonesTransparentes = array();
    protected $_ldap = null;

    public function __construct(Interpro $interpro) {
    	$this->_ldap = new Ldap();
        $file_uri = $interpro->getAttachmentUri("etablissements.csv");
        $this->_interpro = $interpro;
        $this->_csv = $this->_interpro->getEtablissementsArrayFromGrcFile();
        $interproClient = InterproClient::getInstance();
        $this->_zoneClient = ConfigurationZoneClient::getInstance();
        $interpros = $interproClient->getAllInterpros();
        foreach ($interpros as $i) {
        	$this->_interpros[$i] = $interproClient->find($i);
        }
        $this->_zonesTransparentes = array_keys(ConfigurationClient::getCurrent()->getTransparenteZones());
        $zones = $this->_zoneClient->getAllZones();
        foreach ($zones as $z) {
        	$this->_zones[$z] = $this->_zoneClient->find($z);
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
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NO_ASSICES + 1).') "numéro accises" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE + 1).') "numéro tva intracommunautaire" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_NO_CARTE_PROFESSIONNELLE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_NO_CARTE_PROFESSIONNELLE + 1).') "numéro carte professionnelle" manquante');
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
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_NOM])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_NOM + 1).') "compte nom" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_PRENOM])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_PRENOM + 1).') "compte prenom" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_FONCTION])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_FONCTION + 1).') "compte fonction" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL + 1).') "compte email" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_TELEPHONE])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_TELEPHONE + 1).') "compte telephone" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_FAX])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_FAX + 1).') "compte fax" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_ZONES])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_ZONES + 1).') "zones" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CORRESPONDANCES])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CORRESPONDANCES + 1).') "correspondances ids" manquante');
   		}
    	if (!isset($line[EtablissementCsv::COL_CHAMPS_COMPTE_CIEL])) {
   			$errors[] = ('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_CIEL + 1).') "adhesion CIEL" manquante');
   		}
   		if (count($errors) > 0) {
   			$this->_errors[$ligne] = $errors;
   			throw new sfException('has errors');
   		}
    }
    
    protected function completeLine($line)
    {
    	$nb = EtablissementCsv::NB_COL - count($line);
    	while($nb > 0) {
    		$line[] = '';
    		$nb--;
    	}
    	return $line;
    }

    protected function bind($ligne, $etab, $line) {
    	$line = $this->completeLine($line);
    	$this->existLine($ligne, $line);
    	$interpro = InterproClient::getInstance()->matchInterpro(trim($line[EtablissementCsv::COL_INTERPRO]));
    	if ($interpro != $this->_interpro->_id) {
    		throw new sfException('squeeze ligne');
    	} elseif ($line[EtablissementCsv::COL_SIRET]) {
    		$interproRef = EtablissementInterproView::getInstance()->findInterproRefBySiret(trim($line[EtablissementCsv::COL_SIRET]));
    		if ($interproRef && $interproRef['interpro'] != $this->_interpro->_id) {
    			$this->_errors[$ligne] = array('Colonne (indice '.(EtablissementCsv::COL_INTERPRO + 1).') "interpro" referente '.$interproRef['interpro'].' de l\'etablissement '.$interproRef['id']);
    			throw new sfException('has errors');
    		}
    	}
    	$etab->identifiant = trim($line[EtablissementCsv::COL_ID]);
        $etab->num_interne = trim($line[EtablissementCsv::COL_NUM_INTERNE]);
        $etab->siret = trim($line[EtablissementCsv::COL_SIRET]);
        $etab->cni = trim($line[EtablissementCsv::COL_CNI]);
        $etab->cvi = trim($line[EtablissementCsv::COL_CVI]);
        $etab->no_accises = trim($line[EtablissementCsv::COL_NO_ASSICES]);
        $etab->no_tva_intracommunautaire = trim($line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE]);
        $etab->no_carte_professionnelle = trim($line[EtablissementCsv::COL_NO_CARTE_PROFESSIONNELLE]);
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
        $famillesSousFamilles = EtablissementFamilles::getSousFamillesByFamille($etab->famille);
        if (!in_array($etab->sous_famille, array_keys($famillesSousFamilles))) {
        	if (isset($this->_errors[$ligne])) {
        		$merge = $this->_errors[$ligne];
        		$merge[] = 'La sous famille "'.$etab->sous_famille.'" n\'est pas associable à la famille "'.$etab->famille.'"';
        		$this->_errors[$ligne] = $merge;
        	} else {
        		$this->_errors[$ligne] = array('La sous famille "'.$etab->sous_famille.'" n\'est pas associable à la famille "'.$etab->famille.'"');
        	}
        	throw new sfException('has errors');
        }
        $etab->nom = trim($line[EtablissementCsv::COL_NOM]);
        $etab->raison_sociale = trim($line[EtablissementCsv::COL_RAISON_SOCIALE]);
        $validateur = new sfValidatorEmailStrict(array('required' => false));
    	try {
		    $etab->email = $validateur->clean(trim($line[EtablissementCsv::COL_EMAIL]));
		} catch (sfValidatorError $e) {
        	if (isset($this->_errors[$ligne])) {
        		$merge = $this->_errors[$ligne];
        		$merge[] = 'Colonne (indice '.(EtablissementCsv::COL_EMAIL + 1).') "email" non valide';
        		$this->_errors[$ligne] = $merge;
        	} else {
        		$this->_errors[$ligne] = array('Colonne (indice '.(EtablissementCsv::COL_EMAIL + 1).') "email" non valide');
        	}
        	throw new sfException('has errors');
		}
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
		$etab->interpro = $interpro;
		$etab->contrat_mandat = 'CONTRAT-'.trim($line[EtablissementCsv::COL_NUMERO_CONTRAT]);
		if (isset($line[EtablissementCsv::COL_CHAMPS_STATUT]) && trim($line[EtablissementCsv::COL_CHAMPS_STATUT])) {
			$etab->statut = trim($line[EtablissementCsv::COL_CHAMPS_STATUT]);
		} else {
			$etab->statut = Etablissement::STATUT_ACTIF;
		}
		$zones = explode('|', $line[EtablissementCsv::COL_ZONES]);
		$zones = array_merge($zones, $this->_zonesTransparentes);
        $result = array();
        try {
        	foreach ($zones as $zone) {
        		$result[] = $this->_zoneClient->matchZone($zone);
        	}
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
        
        $etab->remove('zones');
        $etab->add('zones');
        foreach ($result as $confZoneId) {
        	$confZone = $this->_zones[$confZoneId];
        	$z = $etab->zones->add($confZoneId);
        	$z->libelle = $confZone->libelle;
        	$z->transparente = $confZone->transparente;
        	$z->administratrice = $confZone->administratrice;
        }
		$correspondances = explode('|', $line[EtablissementCsv::COL_CORRESPONDANCES]);
		$etab->remove('correspondances');
        $etab->add('correspondances');
        foreach ($this->_interpros as $interId => $inter) {
        	if ($inter->correspondances->exist($etab->identifiant)) {
        		$inter->correspondances->remove($etab->identifiant);
        		//$inter->save();
        		$this->_interpros[$interId] = $inter;
        	}
        }
        foreach ($correspondances as $correspondance) {
        	$c = explode(':', $correspondance);
        	if (count($c) == 2) {
        		$i = InterproClient::getInstance()->matchInterpro($c[0]);
        		$interpro = $this->_interpros[$i];
        		$idCorrespondance = trim($c[1]);
        		if ($idCorrespondance == $etab->identifiant) {
        			continue;
        		}
        		$etab->correspondances->add($interpro->_id, $idCorrespondance);
        		$interpro->correspondances->add($etab->identifiant, $idCorrespondance);
        		$this->_interpros[$i] = $interpro;
        	}
        }
        $isCiel = (preg_match("/oui/i", trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_CIEL])))? 1 : 0;
        if ($etab->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
        	$etab->transmission_ciel = $isCiel;
        }
        if ($isCiel && 1==2) { // Desactivation temporaire du controle du num EA
        	$service = new CielService($etab->interpro);
        	$edi = new EtablissementEdi();
  			$result = $service->seed($edi->getXmlFormat(trim($line[EtablissementCsv::COL_NO_ASSICES])));
  			if (strpos($result, '<traderAuthorisation>') === false) {
  				if (isset($this->_errors[$ligne])) {
  					$merge = $this->_errors[$ligne];
  					$merge[] = 'Colonne (indice '.(EtablissementCsv::COL_NO_ASSICES + 1).') "numéro accises" non reconnu par SEED';
  					$this->_errors[$ligne] = $merge;
  				} else {
  					$this->_errors[$ligne] = array('Colonne (indice '.(EtablissementCsv::COL_NO_ASSICES + 1).') "numéro accises" non reconnu par SEED');
  				}
  				throw new sfException('has errors');
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

    public function isEtablissementIdFormatChecked($identifiant) {
        if ($this->_interpro->identifiant == 'IR') {
            return (($identifiant[0] == 'C'||$identifiant[0] == 'T') && substr($identifiant, -3, 1) == '-')? true : false;
        }
        return true;
    }

	public function updateOrCreate()
    {
    	$cpt = 0;
    	$ligne = 0;
      	foreach ($this->_csv as $line) {
      		$ligne++;
            if (!$this->isEtablissementIdFormatChecked(trim($line[EtablissementCsv::COL_ID]))) {
                $this->_errors[$ligne] = array('Colonne (indice '.(EtablissementCsv::COL_ID + 1).') le format d\'identifiant de l\'etablissement est incorrect');
                continue;
            }
	  		$etab = EtablissementClient::getInstance()->retrieveOrCreateById(trim($line[EtablissementCsv::COL_ID]));
	  		$contrat = (trim($line[EtablissementCsv::COL_NUMERO_CONTRAT]))? ContratClient::getInstance()->retrieveById(trim($line[EtablissementCsv::COL_NUMERO_CONTRAT])) : null;
			if ($etab) {
				try {
  					$etab = $this->bind($ligne, $etab, $line);
		  			if ($contrat) {
		  				$etab->compte = $contrat->compte;
		  			}
		  			$etab->save();
                    $societe = $etab->getGenerateSociete();
                    $societe->save();
                    $this->updateSepa($line, $societe);
		  			$this->updateCompte($line, $etab, $contrat, $ligne);
		  			$cpt++;
				} catch (sfException $e) {
					continue;
				}
			}
      	}
      	foreach ($this->_interpros as $interpro) {
      		$interpro->save();
      	}
      	return $cpt;
    }

    private function updateSepa($line, $societe) {
        $inactive = (!trim($line[EtablissementCsv::COL_RIB_CODE_BANQUE])||!trim($line[EtablissementCsv::COL_RIB_CODE_GUICHET])||!trim($line[EtablissementCsv::COL_RIB_NUM_COMPTE])||!trim($line[EtablissementCsv::COL_RIB_CLE]));
        $mandatSepa = MandatSepaClient::getInstance(strtolower(trim($line[EtablissementCsv::COL_INTERPRO])))->findLastBySociete($societe);
        if ($inactive) {
            if($mandatSepa) {
                $mandatSepa->is_actif = 0;
                $mandatSepa->save();
            }
            return;
        }
        if(!$mandatSepa) {
            $mandatSepa = MandatSepaClient::getInstance(strtolower(trim($line[EtablissementCsv::COL_INTERPRO])))->createDoc($societe);
        }
        $mandatSepa->debiteur->banque_nom = trim($line[EtablissementCsv::COL_BANQUE_NOM]);
        $mandatSepa->debiteur->iban = 'XXXX'.trim($line[EtablissementCsv::COL_RIB_CODE_BANQUE]).trim($line[EtablissementCsv::COL_RIB_CODE_GUICHET]).trim($line[EtablissementCsv::COL_RIB_NUM_COMPTE]).trim($line[EtablissementCsv::COL_RIB_CLE]);
        $mandatSepa->is_actif = 1;
        $mandatSepa->is_signe = 1;
        $mandatSepa->save();
    }

    private function updateCompte($line, $etablissement, $contrat, $ligne)
    {
    	if ($contrat) {
    		if ($etablissement->statut == Etablissement::STATUT_ACTIF) {
		    	if ($compte = $contrat->getCompteObject()) {
			    	$compte = $this->bindCompte($line, $compte, $ligne, $etablissement);
			    	if (!$compte->interpro->exist(trim($line[EtablissementCsv::COL_INTERPRO]))) {
			    		$interpro = $compte->interpro->add(trim($line[EtablissementCsv::COL_INTERPRO]));
			    		$interpro->statut = _Compte::STATUT_ATTENTE;
			    	}
			    	if (!$compte->tiers->exist($etablissement->get('_id'))) {
			    		$compte->addEtablissement($etablissement);
			    	}
			    	$compte->save();
			    	$this->_ldap->saveCompte($compte);
		    	}
    		}
    	}
    }

    protected function bindCompte($line, $compte, $ligne, $etablissement) {
    	$compte->nom = trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_NOM]);
        $compte->prenom = trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_PRENOM]);
        $compte->fonction = trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_FONCTION]);
        $validateur = new sfValidatorEmail(array('required' => false));
    	try {
		    $compte->email = $validateur->clean(trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL]));
		} catch (sfValidatorError $e) {
        	if (isset($this->_errors[$ligne])) {
        		$merge = $this->_errors[$ligne];
        		$merge[] = 'Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL + 1).') "email" non valide';
        		$this->_errors[$ligne] = $merge;
        	} else {
        		$this->_errors[$ligne] = array('Colonne (indice '.(EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL + 1).') "email" non valide');
        	}
        	throw new sfException('has errors');
		}
        $compte->telephone = trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_TELEPHONE]);
        $compte->fax = trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_FAX]);
        if ($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
			 if (preg_match("/oui/i", trim($line[EtablissementCsv::COL_CHAMPS_COMPTE_CIEL]))) {
			 	$compte->dematerialise_ciel = 1;
			 } else {
			 	$compte->dematerialise_ciel = 0;
			 }
		}
        
        return $compte;
    }
}

