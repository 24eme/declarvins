<?php

class StatistiquesBilanView extends acCouchdbView
{
	const KEY_INTERPRO = 0;
	const KEY_CAMPAGNE = 1;
	const KEY_ETABLISSEMENT = 2;
	const KEY_PERIODE = 3;
	
	const VALUE_ETABLISSEMENT_NOM = 0;
	const VALUE_ETABLISSEMENT_RAISON_SOCIALE = 1;
	const VALUE_ETABLISSEMENT_SIRET = 2;
	const VALUE_ETABLISSEMENT_CNI = 3;
	const VALUE_ETABLISSEMENT_CVI = 4;
	const VALUE_ETABLISSEMENT_NUM_ACCISES = 5;
	const VALUE_ETABLISSEMENT_NUM_TVA_INTRACOMMUNAUTAIRE = 6;
	const VALUE_ETABLISSEMENT_ADRESSE = 7;
	const VALUE_ETABLISSEMENT_CODE_POSTAL = 8;
	const VALUE_ETABLISSEMENT_COMMUNE = 9;
	const VALUE_ETABLISSEMENT_PAYS = 10;
	const VALUE_ETABLISSEMENT_SERVICE_DOUANE = 11;
	const VALUE_DRM_TOTAL_FIN_DE_MOIS = 12;
	const VALUE_DRM_DATE_SAISIE = 13;
	const VALUE_ETABLISSEMENT_EMAIL = 14;
	const VALUE_ETABLISSEMENT_TELEPHONE = 15;
	const VALUE_ETABLISSEMENT_FAX = 16;
	
	const FIRST_PERIODE = 17;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('statistiques', 'bilan');
    }

    public function findDrmByCampagne($interpro, $campagne) 
    {
    	return $this->client->startkey(array($interpro, $campagne))
                    		->endkey(array($interpro, $campagne, array()))
                    		->getView($this->design, $this->view);
    }
}  