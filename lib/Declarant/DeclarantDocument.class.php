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
        $etablissement = $this->getEtablissementObject();
        if (!$etablissement) {

            throw new sfException(sprintf("L'etablissement %s n'existe pas", $this->getIdentifiant()));
        }
        $declarant = $this->getDeclarant();
        $declarant->nom = $etablissement->nom;
        $declarant->raison_sociale = $etablissement->raison_sociale;
        $declarant->cvi = $etablissement->cvi;
        $declarant->no_accises = $etablissement->no_accises;
        $declarant->adresse = $etablissement->getSiegeAdresses();
        $declarant->commune = $etablissement->siege->commune;
        $declarant->code_postal = $etablissement->siege->code_postal;
        $declarant->region = $etablissement->region;
    }
}