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
    const VRAC_VIEW_VOLPROP = 13;
    const VRAC_VIEW_VOLENLEVE = 14;
    const VRAC_VIEW_PRIXTOTAL = 15;
    const VRAC_VIEW_PRIXUNITAIRE = 16;
    const VRAC_VIEW_PARTCVO = 17;
    const VRAC_VIEW_MILLESIME = 18;
    const VRAC_VIEW_LABELS = 19;
    const VRAC_VIEW_MENTIONS = 20;
    const VRAC_VIEW_MODEDESAISIE = 21;
    const VRAC_VIEW_ACHETEURID = 22;
    const VRAC_VIEW_MANDATAIREID = 23;
    const VRAC_VIEW_VENDEURID = 24;
    const VRAC_VIEW_ACHETEURVAL = 25;
    const VRAC_VIEW_MANDATAIREVAL = 26;
    const VRAC_VIEW_VENDEURVAL = 27;

    public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'soussigneidentifiant', 'Vrac');
    }

    public function findByEtablissement($identifiant) {
      
        return $this->client->startkey(array($identifiant))
                            ->endkey(array($identifiant, array()))
                            ->getView($this->design, $this->view);
    }
    
}  