<?php

class ConfigurationProduitsVracView extends acCouchdbView
{
	const KEY_TYPE_LINE = 0;
	const KEY_HAS_VRAC = 1;
	const KEY_DEPARTEMENT = 2;
	const KEY_LIEU_HASH = 3;
	const KEY_HASH = 4;
	const KEY_CODE = 5;

	const VALUE_LIBELLE_CERTIFICATION = 0;
	const VALUE_LIBELLE_GENRE = 1;
	const VALUE_LIBELLE_APPELLATION = 2;
	const VALUE_LIBELLE_MENTION = 3;
	const VALUE_LIBELLE_LIEU = 4;
	const VALUE_LIBELLE_COULEUR = 5;
	const VALUE_LIBELLE_CEPAGE = 6;

	const VALUE_CODE_CERTIFICATION = 0;
	const VALUE_CODE_GENRE = 1;
	const VALUE_CODE_APPELLATION = 2;
	const VALUE_CODE_MENTION = 3;
	const VALUE_CODE_LIEU = 4;
	const VALUE_CODE_COULEUR = 5;
	const VALUE_CODE_CEPAGE = 6;

	const TYPE_LINE_PRODUITS = 'produits';
	const TYPE_LINE_LIEUX = 'lieux';
	const TYPE_LINE_LABELS = 'labels';

	public static function getInstance() 
	{
        return acCouchdbManager::getView('configuration', 'produits_vrac', 'Configuration');
    }

    public function findProduits() 
    {
    	return $this->client->startkey(array(self::TYPE_LINE_PRODUITS))
              				->endkey(array(self::TYPE_LINE_PRODUITS, array()))
              				->getView($this->design, $this->view);
  	}

  	public function findProduitsVracByDepartement($departement) 
  	{
		return $this->client->startkey(array(self::TYPE_LINE_PRODUITS, 1, $departement))
              				->endkey(array(self::TYPE_LINE_PRODUITS, 1, $departement, array()))
              				->getView($this->design, $this->view);
  	}
	
}  