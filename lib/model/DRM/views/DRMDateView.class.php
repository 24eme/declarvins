<?php
class DRMDateView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_HAS_VRAC = 1;
	const KEY_DATE_SAISIE = 2;
	const KEY_DRM_ID = 3;
	const KEY_DETAIL_HASH = 4;

	const VALUE_TYPE = 0;
	const VALUE_IDENTIFIANT_DECLARANT = 1;
	const VALUE_RAISON_SOCIALE_DECLARANT = 2;
	const VALUE_ANNEE = 3;
	const VALUE_MOIS = 4;
	const VALUE_VERSION = 5;
	const VALUE_ANNEE_PRECEDENTE = 6;
	const VALUE_MOIS_PRECEDENTE = 7;
	const VALUE_VERSION_PRECEDENTE = 8;
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
	const VALUE_DETAIL_TOTAL_DEBUT_MOIS = 26;
	const VALUE_DETAIL_STOCKDEB_BLOQUE = 27;
	const VALUE_DETAIL_STOCKDEB_WARRANTE = 28;
	const VALUE_DETAIL_STOCKDEB_INSTANCE = 29;
	const VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE = 30;
	const VALUE_DETAIL_ENTREES = 31;
	const VALUE_DETAIL_ENTREES_ACHAT = 32;
	const VALUE_DETAIL_ENTREES_RECOLTE = 33;
	const VALUE_DETAIL_ENTREES_REPLI = 34;
	const VALUE_DETAIL_ENTREES_DECLASSEMENT = 35;
	const VALUE_DETAIL_ENTREES_MOUVEMENT = 36;
	const VALUE_DETAIL_ENTREES_CRD = 37;
	const VALUE_DETAIL_SORTIES = 38;
	const VALUE_DETAIL_SORTIES_VRAC = 39;
	const VALUE_DETAIL_SORTIES_EXPORT = 40;
	const VALUE_DETAIL_SORTIES_FACTURES = 41;
	const VALUE_DETAIL_SORTIES_CRD = 42;
	const VALUE_DETAIL_SORTIES_CONSOMMATION = 43;
	const VALUE_DETAIL_SORTIES_PERTES = 44;
	const VALUE_DETAIL_SORTIES_DECLASSEMENT = 45;
	const VALUE_DETAIL_SORTIES_REPLI = 46;
	const VALUE_DETAIL_SORTIES_MOUVEMENT = 47;
	const VALUE_DETAIL_SORTIES_DISTILLATION = 48;
	const VALUE_DETAIL_SORTIES_LIES = 49;
	const VALUE_DETAIL_TOTAL = 50;
	const VALUE_DETAIL_STOCKFIN_BLOQUE = 51;
	const VALUE_DETAIL_STOCKFIN_WARRANTE = 52;
	const VALUE_DETAIL_STOCKFIN_INSTANCE = 53;
	const VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE = 54;
	const VALUE_DATEDESAISIE = 55;
	const VALUE_DATEDESIGNATURE = 56;
	const VALUE_MODEDESAISIE = 57;
	const VALUE_DETAIL_CVO_CODE = 58;
	const VALUE_DETAIL_CVO_TAUX = 59;
	const VALUE_DETAIL_CVO_VOLUME = 60;
	const VALUE_DETAIL_CVO_MONTANT = 61;
	const VALUE_CAMPAGNE = 62;
	const VALUE_IDDRM = 63;
	const VALUE_IDIVSE = 64;
	const VALUE_CONTRAT_NUMERO = 65;
	const VALUE_CONTRAT_VOLUME = 66;
	const VALUE_DRM_REFERENTE = 67;
	const VALUE_CONTRATS_MANQUANTS = 68;
	const VALUE_IGP_MANQUANTS = 69;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('drm', 'date', 'DRM');
    }

    public function findByInterproAndDate($interpro, $date, $onlyVrac = false) 
    {
    	if ($onlyVrac) {
      		return $this->client->startkey(array($interpro, $date, 1))
                    		->endkey(array($interpro, $this->getEndISODateForView(), 1, array()))
                    		->getView($this->design, $this->view);
    	}
      	return $this->client->startkey(array($interpro, $date))
                    		->endkey(array($interpro, $this->getEndISODateForView(), array()))
                    		->getView($this->design, $this->view);
    }

    public function findByInterproAndDates($interpro, $dates, $onlyVrac = false) 
    {
    	if ($onlyVrac) {
      		return $this->client->startkey(array($interpro, $dates['begin'], 1))
                    		->endkey(array($interpro, $dates['end'], 1, array()))
                    		->getView($this->design, $this->view);
    	}
      	return $this->client->startkey(array($interpro, $dates['begin']))
                    		->endkey(array($interpro, $dates['end'], array()))
                    		->getView($this->design, $this->view);
    }
    
    public function getEndISODateForView() 
    {
    	return '9999-99-99T99:99:99'.date('P');
    }

}  