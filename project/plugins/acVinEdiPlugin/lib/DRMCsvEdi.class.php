<?php
class DRMCsvEdi extends CsvFile 
{

    public $erreurs = array();
    public $statut = null;
    public $countryList = array();

    const STATUT_ERREUR = 'ERREUR';
    const STATUT_VALIDE = 'VALIDE';
    const STATUT_WARNING = 'WARNING';
    const TYPE_CAVE = 'CAVE';
    const TYPE_CRD = 'CRD';
    const TYPE_ANNEXE = 'ANNEXE';
    const TYPE_DROITS_SUSPENDUS = 'SUSPENDUS';
    const TYPE_DROITS_ACQUITTES = 'ACQUITTES';
    const CSV_TYPE = 0;
    const CSV_PERIODE = 1;
    const CSV_IDENTIFIANT = 2;
    const CSV_NUMACCISE = 3;
    const CSV_CAVE_CERTIFICATION = 4;
    const CSV_CAVE_GENRE = 5;
    const CSV_CAVE_APPELLATION = 6;
    const CSV_CAVE_MENTION = 7;
    const CSV_CAVE_LIEU = 8;
    const CSV_CAVE_COULEUR = 9;
    const CSV_CAVE_CEPAGE = 10;
    const CSV_CAVE_COMPLEMENT_PRODUIT = 11;
    const CSV_CAVE_PRODUIT = 12;
    const CSV_CAVE_TYPE_DROITS = 13;
    const CSV_CAVE_CATEGORIE_MOUVEMENT = 14;
    const CSV_CAVE_TYPE_MOUVEMENT = 15;
    const CSV_CAVE_VOLUME = 16;
    const CSV_CAVE_EXPORTPAYS = 17;
    const CSV_CAVE_CONTRATID = 18;
    const CSV_CAVE_NUMERODOCUMENT = 19;
    const CSV_CRD_GENRE = 4;
    const CSV_CRD_COULEUR = 5;
    const CSV_CRD_CENTILITRAGE = 6;
    const CSV_CRD_LIBELLE = 12;
    const CSV_CRD_TYPE_DROITS = 13;
    const CSV_CRD_CATEGORIE_KEY = 14;
    const CSV_CRD_TYPE_KEY = 15;
    const CSV_CRD_QUANTITE = 16;
    const CSV_ANNEXE_CATMVT = 14;
    const CSV_ANNEXE_TYPEMVT = 15;
    const CSV_ANNEXE_QUANTITE = 16;
    const CSV_ANNEXE_NONAPUREMENTDATEEMISSION = 17;
    const CSV_ANNEXE_NONAPUREMENTACCISEDEST = 18;
    const CSV_ANNEXE_NUMERODOCUMENT = 19;
    const CSV_NB_TOTAL_COL = 20;

    protected static $permitted_types = array(self::TYPE_CAVE, self::TYPE_CRD, self::TYPE_ANNEXE);
    protected static $permitted_annexes_type_mouvements = array('DEBUT', 'FIN');
    protected static $genres = array('MOU' => 'Mousseux', 'EFF' => 'Effervescent', 'TRANQ' => 'Tranquille');
    
    protected $drm = null;
    protected $csv = null;
    
    public function __construct($file, DRM $drm = null) 
    {
        $this->drm = $drm;
        $this->countryList = ($drm)? $drm->getExportableCountryList() : array();
        //$this->type_annexes_docs = array_merge($this->type_annexes, DRMClient::$drm_documents_daccompagnement);
        parent::__construct($file);
    }

}
