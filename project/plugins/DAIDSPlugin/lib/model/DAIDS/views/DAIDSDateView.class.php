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
	const VALUE_CERTIFICATION = 9;
	const VALUE_CERTIFICATION_CODE = 10;
	const VALUE_GENRE = 11;
	const VALUE_GENRE_CODE = 12;
	const VALUE_APPELLATION = 13;
	const VALUE_APPELLATION_CODE = 14;
	const VALUE_LIEU = 15;
	const VALUE_LIEU_CODE = 16;
	const VALUE_COULEUR = 17;
	const VALUE_COULEUR_CODE = 18;
	const VALUE_CEPAGE = 19;
	const VALUE_CEPAGE_CODE = 20;
	const VALUE_MILLESIME = 21;
	const VALUE_MILLESIME_CODE = 22;
	const VALUE_LABELS = 23;
	const VALUE_LABELS_CODE = 24;
	const VALUE_MENTION_EXTRA = 25;
    const VALUE_STOCK_THEORIQUE = 26;
    const VALUE_STOCK_CHAIS = 27;
    const VALUE_STOCKS_INVENTAIRE_CHAIS = 28;
    const VALUE_CHAIS_DETAILS_ENTREPOT_A = 29;
    const VALUE_CHAIS_DETAILS_ENTREPOT_B = 30;
    const VALUE_CHAIS_DETAILS_ENTREPOT_C = 31;
    const VALUE_STOCKS_PROPRIETE_TIERS = 32;
    const VALUE_STOCK_PROPRIETE = 33;
    const VALUE_STOCKS_PHYSIQUE_CHAIS = 34;
    const VALUE_STOCKS_TIERS = 35;
    const VALUE_STOCK_PROPRIETE_DETAILS_RESERVE = 36;
    const VALUE_STOCK_PROPRIETE_DETAILS_CONDITIONNE = 37;
    const VALUE_STOCK_PROPRIETE_DETAILS_VRAC_VENDU = 38;
    const VALUE_STOCK_PROPRIETE_DETAILS_VRAC_LIBRE = 39;
    const VALUE_TOTAL_MANQUANTS_EXCEDENTS = 40;
    const VALUE_STOCK_MENSUEL_THEORIQUE = 41;
    const VALUE_STOCKS_MOYEN_VINIFIE_VOLUME = 42;
    const VALUE_STOCKS_MOYEN_VINIFIE_TAUX = 43;
    const VALUE_STOCKS_MOYEN_VINIFIE_TOTAL = 44;
    const VALUE_STOCKS_MOYEN_NON_VINIFIE_VOLUME = 45;
    const VALUE_STOCKS_MOYEN_NON_VINIFIE_TOTAL = 46;
    const VALUE_STOCKS_MOYEN_CONDITIONNE_VOLUME = 47;
    const VALUE_STOCKS_MOYEN_CONDITIONNE_TAUX = 48;
    const VALUE_STOCKS_MOYEN_CONDITIONNE_TOTAL = 49;
    const VALUE_TOTAL_PERTES_AUTORISEES = 50;
    const VALUE_TOTAL_MANQUANTS_TAXABLES = 51;
	const VALUE_DATEDESAISIE = 52;
	const VALUE_DATEDESIGNATURE = 53;
	const VALUE_MODEDESAISIE = 54;
	const VALUE_DETAIL_CVO_CODE = 55;
	const VALUE_DETAIL_CVO_TAUX = 56;
	const VALUE_DETAIL_CVO_MONTANT = 57;
	const VALUE_CAMPAGNE = 58;
	const VALUE_IDDAIDS = 59;
	const VALUE_IDIVSE = 60;

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