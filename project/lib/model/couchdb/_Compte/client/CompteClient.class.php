<?php

class CompteClient extends _CompteClient
{
    const TYPE_COMPTE_SOCIETE = "SOCIETE";

    public static function getInstance() {
        return acCouchdbManager::getClient("Compte");
    }

    public function findOrCreateCompteSociete($societe) {
        $compte = null;
        if ($societe->compte_societe) {
            $compte = $this->find($societe->compte_societe);
        }
        return $compte;
    }

    public static function isRealSyncCompte() {
        return false;
    }

}
