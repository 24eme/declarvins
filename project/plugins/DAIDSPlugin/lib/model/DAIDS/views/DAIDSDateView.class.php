<?php
class DAIDSDateView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_DATE_SAISIE = 1;
	const KEY_DRM_ID = 2;
	const KEY_DETAIL_HASH = 3;

	const VALUE_TYPE = 0;
	const VALUE_IDENTIFIANT_DECLARANT = 1;
	const VALUE_RAISON_SOCIALE_DECLARANT = 2;
	const VALUE_ANNEE_DEBUT = 3;
	const VALUE_ANNEE_FIN = 4;
	const VALUE_VERSION = 5;
	const VALUE_PRECEDENTE_ANNEE_DEBUT = 6;
	const VALUE_PRECEDENTE_ANNEE_FIN =7;
	const VALUE_PRECEDENTE_VERSION = 8;
	const VALUE_REFERENTE = 9;
	const VALUE_CERTIFICATION = 10;
	const VALUE_CERTIFICATION_CODE = 11;
	const VALUE_GENRE = 12;
	const VALUE_GENRE_CODE = 13;
	const VALUE_APPELLATION = 14;
	const VALUE_APPELLATION_CODE = 15;
	const VALUE_LIEU = 16;
	const VALUE_LIEU_CODE = 17;
	const VALUE_COULEUR = 18;
	const VALUE_COULEUR_CODE = 19;
	const VALUE_CEPAGE = 20;
	const VALUE_CEPAGE_CODE = 21;
	const VALUE_MILLESIME = 22;
	const VALUE_MILLESIME_CODE = 23;
	const VALUE_LABELS = 24;
	const VALUE_LABELS_CODE = 25;
	const VALUE_MENTION_EXTRA = 26;
    const VALUE_STOCK_THEORIQUE = 27;
    const VALUE_STOCK_CHAIS = 28;
    const VALUE_STOCKS_INVENTAIRE_CHAIS = 29;
    const VALUE_CHAIS_DETAILS_ENTREPOT_A = 30;
    const VALUE_CHAIS_DETAILS_ENTREPOT_B = 31;
    const VALUE_CHAIS_DETAILS_ENTREPOT_C = 32;
    const VALUE_STOCKS_PROPRIETE_TIERS = 33;
    const VALUE_STOCK_PROPRIETE = 34;
    const VALUE_STOCKS_PHYSIQUE_CHAIS = 35;
    const VALUE_STOCKS_TIERS = 36;
    const VALUE_STOCK_PROPRIETE_DETAILS_RESERVE = 37;
    const VALUE_STOCK_PROPRIETE_DETAILS_CONDITIONNE = 38;
    const VALUE_STOCK_PROPRIETE_DETAILS_VRAC_VENDU = 39;
    const VALUE_STOCK_PROPRIETE_DETAILS_VRAC_LIBRE = 40;
    const VALUE_TOTAL_MANQUANTS_EXCEDENTS = 41;
    const VALUE_STOCK_MENSUEL_THEORIQUE = 42;
    const VALUE_STOCKS_MOYEN_VINIFIE_VOLUME = 43;
    const VALUE_STOCKS_MOYEN_VINIFIE_TAUX = 44;
    const VALUE_STOCKS_MOYEN_VINIFIE_TOTAL = 45;
    const VALUE_STOCKS_MOYEN_NON_VINIFIE_VOLUME = 46;
    const VALUE_STOCKS_MOYEN_NON_VINIFIE_TOTAL = 47;
    const VALUE_STOCKS_MOYEN_CONDITIONNE_VOLUME = 48;
    const VALUE_STOCKS_MOYEN_CONDITIONNE_TAUX = 49;
    const VALUE_STOCKS_MOYEN_CONDITIONNE_TOTAL = 50;
    const VALUE_TOTAL_PERTES_AUTORISEES = 51;
    const VALUE_TOTAL_MANQUANTS_TAXABLES = 52;
	const VALUE_DATEDESAISIE = 53;
	const VALUE_DATEDESIGNATURE = 54;
	const VALUE_MODEDESAISIE = 55;
	const VALUE_DETAIL_CVO_CODE = 56;
	const VALUE_DETAIL_CVO_TAUX = 57;
	const VALUE_TOTAL_MANQUANTS_TAXABLES_CVO = 58;
	const VALUE_CAMPAGNE = 59;
	const VALUE_IDDAIDS = 60;
	const VALUE_ID = 61;
	const VALUE_IDIVSE = 62;
	const VALUE_COMMENTAIRES = 63;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('daids', 'date', 'DAIDS');
    }

    public function findByInterproAndDate($interpro, $date) 
    {
      	return $this->client->startkey(array($interpro, $date))
                    		->endkey(array($interpro, $this->getEndISODateForView(), array()))
                    		->getView($this->design, $this->view);
    }
    
    public function getEndISODateForView() 
    {
    	return '9999-99-99T99:99:99'.date('P');
    }

}  