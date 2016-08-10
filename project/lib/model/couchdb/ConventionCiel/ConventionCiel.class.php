<?php
/**
 * Model for ConventionCiel
 *
 */

class ConventionCiel extends BaseConventionCiel {
    
    protected $_compte = null;
    
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
    
    public function getDateSaisieObj()
    {
    	return new DateTime($this->date_saisie);
    }
    
    public function generateFdf()
    {
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
    	$fdfContent  = "<</T(acroform0)/V()>>";
    	$fdfContent .= "<</T(acroform1)/V()>>";
    	$fdfContent .= "<</T(acroform2)/V()>>";
    	$fdfContent .= "<</T(rs)/V({$this->raison_sociale})>>";
    	$fdfContent .= "<</T(siren)/V({$this->no_operateur})>>";
    	$fdfContent .= "<</T(interpro)/V({$this->getInterprofession()})>>";
    	$fdfContent .= "<</T(nom)/V({$this->nom})>>";
    	$fdfContent .= "<</T(prenom)/V({$this->prenom})>>";
    	$fdfContent .= "<</T(fonction)/V({$this->fonction})>>";
    	$fdfContent .= "<</T(email)/V({$this->email})>>";
    	$fdfContent .= "<</T(telephone)/V({$this->telephone})>>";
    	$fdfContent .= "<</T(irs)/V({$this->raison_sociale})>>";
    	$fdfContent .= "<</T(isiren)/V({$this->no_operateur})>>";
    	$fdfContent .= "<</T(iinterpro)/V({$this->getInterprofession()})>>";
    	$fdfContent .= "<</T(datesaisie)/V({$this->getDateSaisieObj()->format('d/m/Y')})>>";
    	
    	$i=0;
    	foreach ($this->etablissements as $etablissement) {
    		$fdfContent .= "<</T(ea{$i})/V({$etablissement->no_accises})>>";
    		$fdfContent .= "<</T(iea{$i})/V({$etablissement->no_accises})>>";
    		$fdfContent .= "<</T(cvi{$i})/V({$etablissement->cvi})>>";
    		$fdfContent .= "<</T(icvi{$i})/V({$etablissement->cvi})>>";
    		$i++;
    	}
    	while ($i<5) {
    		$fdfContent .= "<</T(ea{$i})/V()>>";
    		$fdfContent .= "<</T(iea{$i})/V()>>";
    		$fdfContent .= "<</T(cvi{$i})/V()>>";
    		$fdfContent .= "<</T(icvi{$i})/V()>>";
    		$i++;
    	}
    	
    	$i=0;
    	foreach ($this->habilitations as $habilitation) {
    		if (!$habilitation->droit_teleprocedure) {
    			continue;
    		}
    		$fdfContent .= "<</T(hea{$i})/V({$habilitation->no_accises})>>";
    		$fdfContent .= "<</T(hn{$i})/V({$habilitation->nom})>>";
    		$fdfContent .= "<</T(hp{$i})/V({$habilitation->prenom})>>";
    		$fdfContent .= "<</T(hid{$i})/V({$habilitation->identifiant})>>";
    		$fdfContent .= "<</T(hdr{$i})/V({$habilitation->droit_teleprocedure})>>";
    		$i++;
    	}
    	while ($i<6) {
    		$fdfContent .= "<</T(hea{$i})/V()>>";
    		$fdfContent .= "<</T(hn{$i})/V()>>";
    		$fdfContent .= "<</T(hp{$i})/V()>>";
    		$fdfContent .= "<</T(hid{$i})/V()>>";
    		$fdfContent .= "<</T(hdr{$i})/V()>>";
    		$i++;
    	}
    	
    	$i=0;
    	foreach ($this->habilitations as $habilitation) {
    		if (!$habilitation->droit_telepaiement) {
    			continue;
    		}
    		$mensualisation = ($habilitation->mensualisation)? 'X' : '';
    		$fdfContent .= "<</T(htn{$i})/V({$habilitation->nom})>>";
    		$fdfContent .= "<</T(htp{$i})/V({$habilitation->prenom})>>";
    		$fdfContent .= "<</T(htid{$i})/V({$habilitation->identifiant})>>";
    		$fdfContent .= "<</T(htdr{$i})/V({$habilitation->droit_telepaiement})>>";
    		$fdfContent .= "<</T(htm{$i})/V({$mensualisation})>>";
    		$i++;
    	}
    	while ($i<6) {
    		$fdfContent .= "<</T(htn{$i})/V()>>";
    		$fdfContent .= "<</T(htp{$i})/V()>>";
    		$fdfContent .= "<</T(htid{$i})/V()>>";
    		$fdfContent .= "<</T(htdr{$i})/V()>>";
    		$fdfContent .= "<</T(htm{$i})/V()>>";
    		$i++;
    	}
    	$content = $fdfHeader . $fdfContent . $fdfFooter;
    	return utf8_decode($content);
    }

}