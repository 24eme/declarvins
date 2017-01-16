<?php
class EdiVracpartenaireView extends acCouchdbView
{
	const KEY_ZONE = 0;
	const KEY_STATUT = 1;
	
	const VALUE_VISA = 0;
	const VALUE_VENDEUR_IDENTIFIANT = 1;
	const VALUE_VENDEUR_ACCISES = 2;
	const VALUE_ACHETEUR = 3;
	const VALUE_CERTIFICATION = 4;
	const VALUE_GENRE = 5;
	const VALUE_APPELLATION = 6;
	const VALUE_MENTION = 7;
	const VALUE_LIEU = 8;
	const VALUE_COULEUR = 9;
	const VALUE_CEPAGE = 10;
	const VALUE_LIBELLE = 11;
	const VALUE_MILLESIME = 12;
	const VALUE_VOLUME_PROPOSE = 13;
	const VALUE_VOLUME_ENLEVE = 14;
	

	public static function getInstance() 
	{
        return acCouchdbManager::getView('edi', 'vracpartenaire', 'Vrac');
    }

    public function findByZoneStatut($zone, $statut) 
    {
      	return $this->client->startkey(array($zone, $statut))
                    		->endkey(array($zone, $statut, array()))
                    		->getView($this->design, $this->view);
    }

}  