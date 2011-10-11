<?php

class ImportEtablissementsCsv {

    protected $_interpro = null;
    protected $_compte = null;
    protected $_csv = array();

    public function __construct(Interpro $interpro, _Compte $compte) {
        $file_uri = $interpro->getAttachmentUri("import_etablissements.csv");
        $handler = fopen($file_uri, 'r');
        if (!$handler) {
            throw new Exception('Cannot open csv file anymore');
        }
        $contrat = $compte->getContratObject();
        $this->_interpro = $interpro;
        $this->_compte = $compte;
        $this->_csv = array();
        while (($line = fgetcsv($handler, 0, ";")) !== FALSE) {
            if ($contrat->no_contrat == $line[EtablissementCsv::COL_NUMERO_CONTRAT]) {
                $this->_csv[] = $line;
            }
        }
        fclose($handler);
    }

    public function import() {
        foreach ($this->_csv as $line) {
            $etab = EtablissementClient::getInstance()->retrieveById($line[EtablissementCsv::COL_ID]);
            if (!$etab) {
                $etab = new Etablissement();
                $etab->set('_id', 'ETABLISSEMENT-' . $line[EtablissementCsv::COL_ID]);
            }
            $etab->compte = $this->_compte->get('_id');
            $etab->interpro = $this->_interpro->get('_id');
            $etab->identifiant = $line[EtablissementCsv::COL_ID];
            $etab->num_interne = $line[EtablissementCsv::COL_NUM_INTERNE];
            $etab->siret = $line[EtablissementCsv::COL_SIRET];
            $etab->cni = $line[EtablissementCsv::COL_CNI];
            $etab->cvi = $line[EtablissementCsv::COL_CVI];
            $etab->no_accises = $line[EtablissementCsv::COL_NO_ASSICES];
            $etab->no_tva_intracommunautaire = $line[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE];
            $etab->famille = $line[EtablissementCsv::COL_FAMILLE];
            $etab->sous_famille = $line[EtablissementCsv::COL_SOUS_FAMILLE];
            $etab->nom = $line[EtablissementCsv::COL_NOM_RAISON_SOCIALE];
            $etab->email = $line[EtablissementCsv::COL_EMAIL];
            $etab->telephone = $line[EtablissementCsv::COL_TELEPHONE];
            $etab->fax = $line[EtablissementCsv::COL_FAX];
            $etab->siege->adresse = $line[EtablissementCsv::COL_ADRESSE];
            $etab->siege->code_postal = $line[EtablissementCsv::COL_CODE_POSTAL];
            $etab->siege->commune = $line[EtablissementCsv::COL_COMMUNE];
            $etab->comptabilite->adresse = $line[EtablissementCsv::COL_COMPTA_ADRESSE];
            $etab->comptabilite->code_postal = $line[EtablissementCsv::COL_COMPTA_CODE_POSTAL];
            $etab->comptabilite->commune = $line[EtablissementCsv::COL_COMPTA_CODE_POSTAL];
            $etab->service_douane = $line[EtablissementCsv::COL_SERVICE_DOUANE];
            $etab->save();

            $tiers_compte = $this->_compte->tiers->add($etab->get('_id'));
            $tiers_compte->id = $etab->get('_id');
            $tiers_compte->type = "Etablissement";
            $tiers_compte->nom = $etab->nom;
            $tiers_compte->interpro = $this->_interpro->get('_id');
        }
        $this->_compte->save();
    }

}

