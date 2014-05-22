<?php

class VracSoussigneIdentifiantView extends acCouchdbView
{
    const VRAC_VIEW_STATUT = 0;
    const VRAC_VIEW_NUMCONTRAT = 1;
    const VRAC_VIEW_ACHETEUR_ID = 2;
    const VRAC_VIEW_ACHETEUR_NOM = 3;
    const VRAC_VIEW_ACHETEUR_RAISON_SOCIALE = 4;
    const VRAC_VIEW_VENDEUR_ID = 5;
    const VRAC_VIEW_VENDEUR_NOM = 6;
    const VRAC_VIEW_VENDEUR_RAISON_SOCIALE = 7;
    const VRAC_VIEW_MANDATAIRE_ID = 8;
    const VRAC_VIEW_MANDATAIRE_NOM = 9;    
    const VRAC_VIEW_MANDATAIRE_RAISON_SOCIALE = 10;    
    const VRAC_VIEW_TYPEPRODUIT = 11;
    const VRAC_VIEW_PRODUIT_ID = 12;
    const VRAC_VIEW_PRODUIT_LIBELLE = 13;
    const VRAC_VIEW_VOLPROP = 14;
    const VRAC_VIEW_VOLENLEVE = 15;
    const VRAC_VIEW_PRIXTOTAL = 16;
    const VRAC_VIEW_PRIXUNITAIRE = 17;
    const VRAC_VIEW_PARTCVO = 18;
    const VRAC_VIEW_MILLESIME = 19;
    const VRAC_VIEW_LABELS = 20;
    const VRAC_VIEW_MENTIONS = 21;
    const VRAC_VIEW_MODEDESAISIE = 22;
    const VRAC_VIEW_ACHETEURID = 23;
    const VRAC_VIEW_MANDATAIREID = 24;
    const VRAC_VIEW_VENDEURID = 25;
    const VRAC_VIEW_ACHETEURVAL = 26;
    const VRAC_VIEW_MANDATAIREVAL = 27;
    const VRAC_VIEW_VENDEURVAL = 28;
    const VRAC_VIEW_DATESAISIE = 29;
    const VRAC_VIEW_DATERELANCE = 30;
    const VRAC_VIEW_VOUSETES = 31;
    const VRAC_VIEW_NUM = 32;

    public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'soussigneidentifiant', 'Vrac');
    }

    public function findByEtablissement($identifiant) {
      
        return $this->client->descending(true)
        					->startkey(array($identifiant))
                            ->endkey(array($identifiant, array()))
                            ->getView($this->design, $this->view);
    }
    
}  