<?php
class VracCsvEdi extends CsvFile 
{
    public $statut = null;
    
    const STATUT_ERREUR = 'ERREUR';
    const STATUT_VALIDE = 'VALIDE';
    const STATUT_WARNING = 'WARNING';
    
    const CSV_VISA = 0;
    const CSV_IDENTIFIANTVENDEUR = 1;
    const CSV_ACCISESVENDEUR = 2;
    const CSV_RSACHETEUR = 3;
    const CSV_CAVE_CERTIFICATION = 4;
    const CSV_CAVE_GENRE = 5;
    const CSV_CAVE_APPELLATION = 6;
    const CSV_CAVE_MENTION = 7;
    const CSV_CAVE_LIEU = 8;
    const CSV_CAVE_COULEUR = 9;
    const CSV_CAVE_CEPAGE = 10;
    const CSV_CAVE_PRODUIT = 11;
    const CSV_CAVE_MILLESIME = 12;
    const CSV_CAVE_VOLINITIAL = 13;
    const CSV_CAVE_VOLRETIRE = 14;
    const CSV_NB_TOTAL_COL = 15;

    
    protected $vrac = null;
    protected $csv = null;
    
    public function __construct($file, VRAC $vrac = null) 
    {
        $this->vrac = $vrac;
        parent::__construct($file);
    }

}
