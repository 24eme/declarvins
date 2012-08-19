<?php

class VracSoussigneIdentifiantView extends acCouchdbView
{
    const VRAC_VIEW_STATUT = 0;
    const VRAC_VIEW_NUMCONTRAT = 1;
    const VRAC_VIEW_ACHETEUR_ID = 2;
    const VRAC_VIEW_ACHETEUR_NOM = 3;
    const VRAC_VIEW_VENDEUR_ID = 4;
    const VRAC_VIEW_VENDEUR_NOM = 5;
    const VRAC_VIEW_MANDATAIRE_ID = 6;
    const VRAC_VIEW_MANDATAIRE_NOM = 7;    
    const VRAC_VIEW_TYPEPRODUIT = 8;
    const VRAC_VIEW_PRODUIT_ID = 9;
    const VRAC_VIEW_VOLPROP = 10;
    const VRAC_VIEW_VOLENLEVE = 11;

    public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'soussigneidentifiant', 'Vrac');
    }

    public function findByEtablissement($identifiant) {
      
        return $this->client->startkey(array($identifiant))
                            ->endkey(array($identifiant, array()))
                            ->getView($this->design, $this->view);
    }
    
}  