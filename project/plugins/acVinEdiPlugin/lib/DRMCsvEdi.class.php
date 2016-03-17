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
    const TYPE_ANNEXE_NONAPUREMENT = 'NONAPUREMENT';
    const TYPE_ANNEXE_SUCRE = 'SUCRE';
    const TYPE_ANNEXE_OBSERVATIONS = 'OBSERVATIONS';
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
    const CSV_CAVE_CATEGORIE_MOUVEMENT = 11;
    const CSV_CAVE_TYPE_MOUVEMENT = 12;
    const CSV_CAVE_VOLUME = 13;
    const CSV_CAVE_EXPORTPAYS = 14;
    const CSV_CAVE_CONTRATID = 15;
    const CSV_CAVE_COMMENTAIRE = 16;
    const CSV_CRD_GENRE = 4;
    const CSV_CRD_COULEUR = 5;
    const CSV_CRD_CENTILITRAGE = 6;
    const CSV_CRD_CATEGORIE_KEY = 11;
    const CSV_CRD_TYPE_KEY = 12;
    const CSV_CRD_QUANTITE = 13;
    const CSV_ANNEXE_TYPEANNEXE = 11;
    const CSV_ANNEXE_TYPEMVT = 12;
    const CSV_ANNEXE_QUANTITE = 13;
    const CSV_ANNEXE_NONAPUREMENTDATEEMISSION = 14;
    const CSV_ANNEXE_NONAPUREMENTACCISEDEST = 15;
    const CSV_ANNEXE_NUMERODOCUMENT = 16;
    const CSV_ANNEXE_OBSERVATION = 17;

    protected static $permitted_types = array(self::TYPE_CAVE, self::TYPE_CRD, self::TYPE_ANNEXE);
    protected static $permitted_annexes_type_mouvements = array('DEBUT', 'FIN');
    protected static $genres = array('MOU' => 'Mousseux', 'EFF' => 'Effervescent', 'TRANQ' => 'Tranquille');
    protected $type_annexes = array(self::TYPE_ANNEXE_NONAPUREMENT => 'Non Apurement', self::TYPE_ANNEXE_SUCRE => 'Sucre', self::TYPE_ANNEXE_OBSERVATIONS => 'Observations');
    
    protected $drm = null;
    protected $csv = null;
    
    public function __construct($file, DRM $drm = null) 
    {
        $this->drm = $drm;
        //$this->type_annexes_docs = array_merge($this->type_annexes, DRMClient::$drm_documents_daccompagnement);
        //$this->buildCountryList();
        parent::__construct($file);
    }

    public function buildCountryList() 
    {
        $countryList = ConfigurationClient::getInstance()->getCountryList();
        $match_array = array();
        foreach ($countryList as $keyUpper => $countryString) {
            $match_array[$keyUpper . '_' . strtolower($keyUpper)] = $countryString;
            $match_array[$countryString] = $countryString;
        }
        $this->countryList = array_merge($countryList, $match_array);
    }

}
