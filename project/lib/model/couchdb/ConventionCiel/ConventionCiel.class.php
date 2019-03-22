<?php
/**
 * Model for ConventionCiel
 *
 */

class ConventionCiel extends BaseConventionCiel {
    
    protected $_compte = null;
    
    public static $droits = array('consulter' => 'Consulter', 'gérer' => 'Déclarer', 'adhérer' => 'Adhérer', 'valider' => 'Télépayer', 'adhérer_valider' => 'Adhérer et Télépayer');
    public static $mandataire = array('teledeclaration' => 'Mandataire télédeclaration', 'telepaiement' => 'Mandataire télépaiement');
    /**
     * @return _Compte
     */
    public function getCompteObject() {
        if (is_null($this->_compte)) {
            $this->_compte = acCouchdbManager::getClient('_Compte')->retrieveDocumentById($this->compte);
        }
        return $this->_compte;
    }
    
    public function getInterprofession() 
    {
    	if ($this->interpro == 'INTERPRO-IR') {
    		return 'InterRhône';
    	}
    	if ($this->interpro == 'INTERPRO-CIVP') {
    		return 'CIVP';
    	}
    	return $this->interpro;
    }
    
    public function getEmailInterprofession() 
    {
    	if ($this->interpro) {
    		$interpro = InterproClient::getInstance()->find($this->interpro);
    		return $interpro->email_contrat_inscription;
    	}
    	return '';
    }
    
    public function getDateSaisieObj()
    {
    	return new DateTime($this->date_saisie);
    }
    
    public function getObj($date)
    {
    	return new DateTime($this->{$date});
    }
    
    public function getTypeDroit($droitTeleprocedure, $droitTelepaiement) 
    {
    	if ($droitTeleprocedure == 'consulter') {
    		$droits = 'C';
    	} elseif ($droitTeleprocedure == 'gérer') {
    		$droits = 'D';
    	} else {
    		$droits = '';
    	}
    	if ($droitTelepaiement == 'adhérer') {
    		if ($droits) {
    			$droits .= ' / A';
    		} else {
    			$droits .= 'A';
    		}
    	} elseif ($droitTelepaiement == 'valider') {
    		if ($droits) {
    			$droits .= ' / T';
    		} else {
    			$droits .= 'T';
    		}
    	} elseif ($droitTelepaiement == 'adhérer_valider') {
    		if ($droits) {
    			$droits .= ' / A / T';
    		} else {
    			$droits .= 'A / T';
    		}
    	} else {
    		$droits .= '';
    	}
    	return $droits;
    }
    
    public function getHabilitationsSaisies()
    {
    	$hab = array();
    	foreach ($this->habilitations as $h) {
    		if ($h->no_accises && $h->identifiant) {
    			$hab[] = $h;
    		}
    	}
    	return $hab;
    }
    
    public function getEtablissementsSaisies()
    {
    	$etab = array();
    	foreach ($this->etablissements as $e) {
    		$etab[] = $e;
    	}
    	return $etab;
    }
    
    public function cleanHabilitations() {
    	$hab = $this->getHabilitationsSaisies();
    	$this->remove('habilitations');
    	$this->add('habilitations');
    	foreach ($hab as $h) {
    		$this->habilitations->add(null, $h);
    	}
    }
    
    public function generateFdf()
    {

    	$contrat = ($c = $this->getCompteObject())? str_replace('CONTRAT-', '', $c->contrat) : null;
    	$fdfHeader = 
<<<FDF
%FDF-1.2
%âãÏÓ
1 0 obj 
<<
/FDF 
<<
/Fields [
FDF;
	$fdfFooter = 
<<<FDF
]
>>
>>
endobj 
trailer

<<
/Root 1 0 R
>>
%%EOF
FDF;
$etablissements = $this->getEtablissementsSaisies();
$habilitations = $this->getHabilitationsSaisies();
$fdfContent  = "";
$fdfContent  .= "<</T(Zone de texte 7)/V({$this->raison_sociale})>>";
$fdfContent  .= "<</T(Zone de texte 11)/V({$this->no_operateur})>>";
$fdfContent  .= "<</T(Zone de texte 11_2)/V({$this->no_siret_payeur})>>";
$fdfContent  .= "<</T(Zone de texte 7_2)/V({$this->adresse}\n{$this->code_postal} {$this->commune})>>";
$fdfContent  .= "<</T(Zone de texte 11_3)/V({$this->email_beneficiaire})>>";
$fdfContent  .= "<</T(Zone de texte 11_4)/V({$this->telephone_beneficiaire})>>";
$fdfContent  .= "<</T(Zone de texte 11_5)/V({$this->getObj('date_fin_exercice')->format('d/m/Y')})>>";
$fdfContent  .= "<</T(Zone de texte 11_6)/V({$this->getObj('date_ciel')->format('d/m/Y')})>>";
if (isset($etablissements[0])) {
	$fdfContent  .= "<</T(Zone de texte 3)/V({$etablissements[0]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_3)/V({$etablissements[0]->cvi})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_3)/V()>>";
}
if (isset($etablissements[1])) {
	$fdfContent  .= "<</T(Zone de texte 3_2)/V({$etablissements[1]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_4)/V({$etablissements[1]->cvi})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3_2)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_4)/V()>>";
}
$fdfContent  .= "<</T(Zone de texte 3_5)/V({$this->getInterprofession()})>>";
$fdfContent  .= "<</T(Zone de texte 1)/V({$this->nom})>>";
$fdfContent  .= "<</T(Zone de texte 9)/V({$this->prenom})>>";
$fdfContent  .= "<</T(Zone de texte 9_2)/V({$this->telephone})>>";
$fdfContent  .= "<</T(Zone de texte 9_3)/V({$this->email})>>";
if ($this->representant_legal) {
	$fdfContent  .= "<</T(Bouton radio 1)/V(1)>>";
} else {
	$fdfContent  .= "<</T(Bouton radio 1)/V(2)>>";
}
if ($this->mandataire == 'teledeclaration') {
	$fdfContent  .= "<</T(Bouton radio 1_2)/V(1)>>";
} elseif ($this->mandataire == 'telepaiement') {
	$fdfContent  .= "<</T(Bouton radio 1_2)/V(2)>>";
} else {
	$fdfContent  .= "<</T(Bouton radio 1_2)/V(Off)>>";
}
if (isset($habilitations[0])) {
	$fdfContent  .= "<</T(Zone de texte 3_6)/V({$habilitations[0]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_7)/V({$habilitations[0]->identifiant})>>";
	$fdfContent  .= "<</T(Zone de texte 3_8)/V({$this->getTypeDroit($habilitations[0]->droit_teleprocedure, $habilitations[0]->droit_telepaiement)})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3_6)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_7)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_8)/V()>>";
}
if (isset($habilitations[1])) {
	$fdfContent  .= "<</T(Zone de texte 3_9)/V({$habilitations[1]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_13)/V({$habilitations[1]->identifiant})>>";
	$fdfContent  .= "<</T(Zone de texte 3_11)/V({$this->getTypeDroit($habilitations[1]->droit_teleprocedure, $habilitations[1]->droit_telepaiement)})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3_9)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_13)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_11)/V()>>";
}
if (isset($habilitations[2])) {
	$fdfContent  .= "<</T(Zone de texte 3_10)/V({$habilitations[2]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_14)/V({$habilitations[2]->identifiant})>>";
	$fdfContent  .= "<</T(Zone de texte 3_12)/V({$this->getTypeDroit($habilitations[2]->droit_teleprocedure, $habilitations[2]->droit_telepaiement)})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3_10)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_14)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_12)/V()>>";
}
$fdfContent  .= "<</T(Zone de texte 2)/V()>>";
$fdfContent  .= "<</T(Zone de texte 2_2)/V({$this->getDateSaisieObj()->format('d/m/Y')})>>";
$fdfContent  .= "<</T(Zone de texte 7_3)/V({$this->raison_sociale})>>";
$fdfContent  .= "<</T(Zone de texte 11_7)/V({$this->no_operateur})>>";
$fdfContent  .= "<</T(Zone de texte 7_4)/V({$this->adresse}\n{$this->code_postal} {$this->commune})>>";
$fdfContent  .= "<</T(Zone de texte 11_8)/V({$this->email_beneficiaire})>>";
$fdfContent  .= "<</T(Zone de texte 11_9)/V({$this->telephone_beneficiaire})>>";
$fdfContent  .= "<</T(Zone de texte 11_10)/V({$this->getObj('date_fin_exercice')->format('d/m/Y')})>>";
$fdfContent  .= "<</T(Zone de texte 11_11)/V({$this->getObj('date_ciel')->format('d/m/Y')})>>";
if (isset($etablissements[0])) {
	$fdfContent  .= "<</T(Zone de texte 3_15)/V({$etablissements[0]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_16)/V({$etablissements[0]->cvi})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3_15)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_16)/V()>>";
}
if (isset($etablissements[1])) {
	$fdfContent  .= "<</T(Zone de texte 3_18)/V({$etablissements[1]->no_accises})>>";
	$fdfContent  .= "<</T(Zone de texte 3_19)/V({$etablissements[1]->cvi})>>";
} else {
	$fdfContent  .= "<</T(Zone de texte 3_18)/V()>>";
	$fdfContent  .= "<</T(Zone de texte 3_19)/V()>>";
}
$fdfContent  .= "<</T(Zone de texte 3_20)/V()>>";
$fdfContent  .= "<</T(Zone de texte 3_21)/V()>>";
$fdfContent  .= "<</T(Zone de texte 3_22)/V()>>";
$fdfContent  .= "<</T(Zone de texte 3_23)/V()>>";
$fdfContent  .= "<</T(Zone de texte 3_24)/V()>>";
$fdfContent  .= "<</T(Zone de texte 3_25)/V()>>";
$fdfContent  .= "<</T(Zone de texte 3_17)/V({$this->getInterprofession()})>>";
    	$content = $fdfHeader . $fdfContent . $fdfFooter;
    	return utf8_decode($content);
    }

}