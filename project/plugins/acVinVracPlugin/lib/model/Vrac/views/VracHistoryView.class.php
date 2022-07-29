<?php

class VracHistoryView extends acCouchdbView
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
    const VRAC_VERSION = 33;
    const VRAC_REFERENTE = 34;
    const VRAC_OIOC_ID = 35;
    const VRAC_OIOC_STATUT = 36;
    const VRAC_OIOC_DATERECEPTION = 37;
    const VRAC_OIOC_DATETRAITEMENT = 38;
    const VRAC_POIDS = 39;

	public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'history', 'Vrac');
    }

	public function findByStatutAndInterpro($statut, $interpro, $pluriannuel = 0) {
        return $this->client->startkey(array($pluriannuel, $statut, $interpro))
                    		->endkey(array($pluriannuel, $statut, $interpro, array()))
                            ->getView($this->design, $this->view);
    }

	public function findLastByInterpro($interpro, $pluriannuel = 0) {
		$date_fin = date('c');
		$date_debut = date('c', mktime(0, 0, 0, date("m"), date("d"), date("Y")-1));
        return $this->client->startkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION, $interpro, $date_debut))
                    		->endkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION, $interpro, $date_fin, array()))
                            ->getView($this->design, $this->view);
    }


	public function findLastByStatutAndInterpro($statut, $interpro, $pluriannuel = 0) {
        $date_fin = date('c');
        $date_debut = date('c', mktime(0, 0, 0, date("m"), date("d"), date("Y")-1));
		if (!$statut||in_array($statut, array(VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION, VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION))) {
            $enCours = $this->client->startkey(array($pluriannuel, $statut, $interpro))->endkey(array($pluriannuel, $statut, $interpro, array()))->getView($this->design, $this->view)->rows;
            $attValidation = $this->client->startkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION, $interpro, $date_debut))->endkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION, $interpro, $date_fin, array()))->getView($this->design, $this->view)->rows;
            $attAnnulation = $this->client->startkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION, $interpro, $date_debut))->endkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION, $interpro, $date_fin, array()))->getView($this->design, $this->view)->rows;
            return array_merge($enCours, $attValidation, $attAnnulation);
		}
        return $this->client->startkey(array($pluriannuel, $statut, $interpro, $date_debut))
                    		->endkey(array($pluriannuel, $statut, $interpro, $date_fin, array()))
                            ->getView($this->design, $this->view)->rows;
    }

	public function findLast($pluriannuel = 0) {

        return $this->client->startkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION))
                    		->endkey(array($pluriannuel, VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION, array()))
                            ->getView($this->design, $this->view);
    }

}
