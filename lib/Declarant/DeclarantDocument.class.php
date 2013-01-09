<?php

class DeclarantDocument
{
    protected $document;
    protected $etablissement = null;

    public function __construct(acCouchdbDocument $document)
    {
        $this->document = $document;
    }
    
    public function getIdentifiant()
    {
        return $this->document->identifiant;
    }

    public function getDeclarant()
    {
        return $this->document->declarant;
    }
    
    public function getEtablissementObject() {
        if(is_null($this->etablissement)) {
            $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($this->getIdentifiant());
        }

        return $this->etablissement;
    }

    public function storeDeclarant()
    {
        $etabissement = $this->getEtablissementObject();
        if (!$etabissement) {

            throw new sfException(sprintf("L'etablissement %s n'existe pas", $this->getIdentifiant()));
        }
        $declarant = $this->getDeclarant();
        $declarant->nom = $etabissement->nom;
        $declarant->cvi = $etabissement->cvi;
        $declarant->num_accise = $etabissement->no_accises;
        $declarant->num_tva_intracomm = $etabissement->no_tva_intracommunautaire;
        $declarant->adresse = $etabissement->siege->adresse;        
        $declarant->commune = $etabissement->siege->commune;
        $declarant->code_postal = $etabissement->siege->code_postal;
        $declarant->raison_sociale = $etabissement->raison_sociale;
        $declarant->region = $etabissement->region;
    }
}