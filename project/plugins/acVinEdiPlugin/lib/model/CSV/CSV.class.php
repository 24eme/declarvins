<?php

/**
 * Model for CSV
 *
 */
class CSV extends BaseCSV {

    public function __construct() {
        parent::__construct();
    }

    public function getFileContent() {
        return file_get_contents($this->getAttachmentUri($this->getFileName()));
    }

    public function getFileName() {
        return ($this->periode)? 'import_edi_' . $this->identifiant . '_' . $this->periode . '.csv' : 'import_edi_' . $this->identifiant . '.csv';
    }

    public function hasErreurs() {
        return count($this->erreurs);
    }

    public function addErreur($erreur) {
        $erreurNode = $this->erreurs->getOrAdd(uniqid());
        $erreurNode->num_ligne = $erreur->num_ligne;
        $erreurNode->csv_erreur = $erreur->erreur_csv;
        $erreurNode->diagnostic = $erreur->raison;
        if (isset($erreur->id)) {
        	$erreurNode->id = $erreur->id;
        }
        return $erreurNode;
    }

    public function clearErreurs() {
        $this->remove('erreurs');
        $this->add('erreurs');
        $this->statut = null;
    }

}
