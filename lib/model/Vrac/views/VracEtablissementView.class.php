<?php
class VracEtablissementView extends acCouchdbView
{
	const KEY_ETABLISSEMENT_ID = 0;
	const KEY_DATES_SAISIE = 1;
	const KEY_VRAC_ID = 2;
	const KEY_PRODUIT_HASH = 3;

	const VALUE_VRAC_ID = 0;
	const VALUE_DATE_SAISIE = 1;
	const VALUE_DATE_VALIDATION = 2;
	const VALUE_ACHETEUR_ID = 3;	
	const VALUE_ACHETEUR_CVI = 4;
	const VALUE_ACHETEUR_SIRET = 5;
	const VALUE_ACHETEUR_NOM = 6;
	const VALUE_VENDEUR_ID = 7;
	const VALUE_VENDEUR_CVI = 8;
	const VALUE_VENDEUR_SIRET = 9;
	const VALUE_VENDEUR_NOM = 10;
	const VALUE_MANDATAIRE_ID = 11;
	const VALUE_MANDATAIRE_SIRET = 12;
	const VALUE_MANDATAIRE_NOM = 13;
	const VALUE_TYPE_CONTRAT_LIBELLE = 14;	
	const VALUE_PRODUIT_CERTIFICATION_LIBELLE = 15;
	const VALUE_PRODUIT_CERTIFICATION_CODE = 16;
	const VALUE_PRODUIT_GENRE_LIBELLE = 17;
	const VALUE_PRODUIT_GENRE_CODE = 18;
	const VALUE_PRODUIT_APPELLATION_LIBELLE = 19;
	const VALUE_PRODUIT_APPELLATION_CODE = 20;
	const VALUE_PRODUIT_LIEU_LIBELLE = 21;
	const VALUE_PRODUIT_LIEU_CODE = 22;
	const VALUE_PRODUIT_COULEUR_LIBELLE = 23;
	const VALUE_PRODUIT_COULEUR_CODE = 24;
	const VALUE_PRODUIT_CEPAGE_LIBELLE = 25;
	const VALUE_PRODUIT_CEPAGE_CODE = 26;
	const VALUE_MILLESIME = 27;
	const VALUE_MILLESIME_CODE = 28;
	const VALUE_LABELS_LIBELLE = 29;
	const VALUE_LABELS_CODE = 30;
	const VALUE_MENTIONS = 31;
	const VALUE_MENTIONS_CODE = 32;
	const VALUE_CAS_PARTICULIER_LIBELLE = 33;
	const VALUE_PREMIERE_MISE_EN_MARCHE = 34;
	const VALUE_ANNEXE = 35;
	const VALUE_VOLUME_PROPOSE = 36;
	const VALUE_PRIX_UNITAIRE = 37;
	const VALUE_TYPE_PRIX = 38;
	const VALUE_DETERMINATION_PRIX = 39;
	const VALUE_EXPORT = 40;
	const VALUE_CONDITIONS_PAIEMENT_LIBELLE = 41;
	const VALUE_REFERENCE_CONTRAT_PLURIANNUEL = 42;
	const VALUE_VIN_LIVRE = 43;
	const VALUE_DATE_DEBUT_RETIRAISON = 44;
	const VALUE_DATE_LIMITE_RETIRAISON = 45;
	const VALUE_PAIEMENTS_DATE = 46;
	const VALUE_PAIEMENTS_VOLUME = 47;
	const VALUE_PAIEMENTS_MONTANT = 48;
	const VALUE_LOT_NUMERO = 49;
	const VALUE_LOT_CUVES_NUMERO = 50;
	const VALUE_LOT_CUVES_VOLUME = 51;
	const VALUE_LOT_CUVES_DATE = 52;
	const VALUE_LOT_ASSEMBLAGE = 53;
	const VALUE_LOT_MILLESIMES_ANNEE = 54;
	const VALUE_LOT_MILLESIMES_POURCENTAGE = 55;
	const VALUE_LOT_DEGRE = 56;
	const VALUE_LOT_PRESENCE_ALLERGENES = 57;
	const VALUE_STATUT = 58;
	const VALUE_COMMENTAIRE = 59;
	const VALUE_VERSION = 60;
	const VALUE_REFERENTE = 61;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('vrac', 'etablissement', 'Vrac');
    }

    public function findByEtablissement($etablissement, $date = null) 
    {
    	if (!$date) {
      		return $this->client->startkey(array($etablissement))
                    		->endkey(array($etablissement, array()))
                    		->getView($this->design, $this->view);
    	}
      	return $this->client->startkey(array($etablissement, $date))
                    		->endkey(array($etablissement, $this->getEndISODateForView(), array()))
                    		->getView($this->design, $this->view);
    }
    
    public function getEndISODateForView() 
    {
    	return '9999-99-99T99:99:99'.date('P');
    }

}  