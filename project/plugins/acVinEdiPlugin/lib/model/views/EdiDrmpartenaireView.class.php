<?php
class EdiDrmpartenaireView extends acCouchdbView
{
	const KEY_INTERPRO = 0;
	const KEY_DATE = 1;
	
	const VALUE_KEY = 0;
	const VALUE_PERIODE = 1;
	const VALUE_IDENTIFIANT = 2;
	const VALUE_ACCISES = 3;
	const VALUE_CERTIFICATION = 4;
	const VALUE_GENRE = 5;
	const VALUE_APPELLATION = 6;
	const VALUE_MENTION = 7;
	const VALUE_LIEU = 8;
	const VALUE_COULEUR = 9;
	const VALUE_CEPAGE = 10;
	const VALUE_LABEL = 11;
	const VALUE_LIBELLE = 12;
	const VALUE_TYPE_DRM = 13;
	const VALUE_CATEGORIE_MVT = 14;
	const VALUE_TYPE_MVT = 15;
	const VALUE_VALEUR = 16;
	const VALUE_ANNEXE1 = 17;
	const VALUE_ANNEXE2 = 18;
	const VALUE_ANNEXE3 = 19;
	

	public static function getInstance() 
	{
        return acCouchdbManager::getView('edi', 'drmpartenaire', 'DRM');
    }

    public function findByInterproDate($interpro, $date) 
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