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
    const TYPE_CONTRAT = 'RETIRAISON';
    const TYPE_CRD = 'CRD';
    const TYPE_ANNEXE = 'ANNEXE';
    const TYPE_ANNEXE_NONAPUREMENT = 'NON_APUREMENT';
    const TYPE_ANNEXE_DOCUMENT = 'DOCUMENT';
    const TYPE_ANNEXE_SUCRE = 'SUCRE';
    const TYPE_ANNEXE_OBSERVATIONS = 'OBSERVATIONS';
    const TYPE_ANNEXE_STATISTIQUES = 'STATISTIQUES';
    const TYPE_DROITS_SUSPENDUS = 'SUSPENDUS';
    const TYPE_DROITS_ACQUITTES = 'ACQUITTES';
    const CSV_TYPE = 0;
    const CSV_PERIODE = 1;
    const CSV_IDENTIFIANT = 2;
    const CSV_NUMACCISE = 3;
    const CSV_CAVE_TYPE_DROITS = 4;
    const CSV_CAVE_CERTIFICATION = 5;
    const CSV_CAVE_GENRE = 6;
    const CSV_CAVE_APPELLATION = 7;
    const CSV_CAVE_MENTION = 8;
    const CSV_CAVE_LIEU = 9;
    const CSV_CAVE_COULEUR = 10;
    const CSV_CAVE_CEPAGE = 11;
    const CSV_CAVE_PRODUIT = 12;
    const CSV_CAVE_CATEGORIE_MOUVEMENT = 13;
    const CSV_CAVE_TYPE_MOUVEMENT = 14;
    const CSV_CAVE_VOLUME = 15;
    const CSV_CAVE_EXPORTPAYS = 16;
    const CSV_CAVE_CONTRATID = 17;
    const CSV_CAVE_NUMERODOCUMENT = 18;
    const CSV_CAVE_COMMENTAIRE = 19;
    const CSV_CONTRAT_TYPE_DROITS = 4;
    const CSV_CONTRAT_CERTIFICATION = 5;
    const CSV_CONTRAT_GENRE = 6;
    const CSV_CONTRAT_APPELLATION = 7;
    const CSV_CONTRAT_MENTION = 8;
    const CSV_CONTRAT_LIEU = 9;
    const CSV_CONTRAT_COULEUR = 10;
    const CSV_CONTRAT_CEPAGE = 11;
    const CSV_CONTRAT_PRODUIT = 12;
    const CSV_CONTRAT_VOLUME = 15;
    const CSV_CONTRAT_CONTRATID = 17;
    const CSV_CRD_TYPE_DROITS = 4;
    const CSV_CRD_GENRE = 5;
    const CSV_CRD_COULEUR = 6;
    const CSV_CRD_CENTILITRAGE = 7;
    const CSV_CRD_LIBELLE = 12;
    const CSV_CRD_CATEGORIE_KEY = 13;
    const CSV_CRD_TYPE_KEY = 14;
    const CSV_CRD_QUANTITE = 15;
    const CSV_ANNEXE_TYPEANNEXE = 4;
    const CSV_ANNEXE_CATMVT = 13;
    const CSV_ANNEXE_TYPEMVT = 14;
    const CSV_ANNEXE_QUANTITE = 15;
    const CSV_ANNEXE_NONAPUREMENTDATEEMISSION = 16;
    const CSV_ANNEXE_NONAPUREMENTACCISEDEST = 17;
    const CSV_ANNEXE_NUMERODOCUMENT = 18;
    const CSV_ANNEXE_OBSERVATION = 19;
    const CSV_NB_TOTAL_COL = 20;

    protected static $permitted_types = array(self::TYPE_CAVE, self::TYPE_CRD, self::TYPE_ANNEXE, self::TYPE_CONTRAT);
    protected static $permitted_annexes_type_mouvements = array('DEBUT', 'FIN');
    protected static $genres = array('MOU' => 'Mousseux', 'EFF' => 'Effervescent', 'TRANQ' => 'Tranquille');
    protected $type_annexes = array(self::TYPE_ANNEXE_NONAPUREMENT => 'Non Apurement', self::TYPE_ANNEXE_SUCRE => 'Sucre', self::TYPE_ANNEXE_OBSERVATIONS => 'Observations');
    
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
