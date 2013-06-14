<?php
class CompteDeclarantsView extends acCouchdbView
{
	
	const KEY_VALIDE = 0;
	const KEY_NUMERO_CONTRAT = 1;
	const KEY_NOM = 2;
	const KEY_PRENOM = 3;
	const KEY_LOGIN = 4;
	const KEY_EMAIL = 5;
	const KEY_RAISON_SOCIALE = 6;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('compte', 'declarants', '_Compte');
    }

    public function findAll() 
    {
    	return $this->client->getView($this->design, $this->view);
  	}
  	
	public function formatComptes($interpro = null, $format = "%n% %p% %rs% (%l% %e% %m%)") 
	{
  		$comptes_format = array();
  		$comptes = $this->findAll();
  		foreach($comptes->rows as $compte) {
  			$interpros = json_decode(json_encode($compte->value), true);
  			if (count($interpros) > 0) {
	  			if ($interpro && in_array($interpro, array_keys($interpros))) {
	  				$comptes_format[$compte->key[self::KEY_NUMERO_CONTRAT]] = $this->formatCompte($compte->key, $format);
	  			}
  			} else {
  				$comptes_format[$compte->key[self::KEY_NUMERO_CONTRAT]] = $this->formatCompte($compte->key, $format);
  			}
        }
        ksort($comptes_format);

        return $comptes_format;
  	}

  	protected function formatCompte($compte, $format = "%n% %p% %rs% (%l% %e% %m%)") {
  		
        $format_index = array('%n%' => self::KEY_NOM,
		                      '%p%' => self::KEY_PRENOM,
		                      '%l%' => self::KEY_LOGIN,
		                      '%e%' => self::KEY_EMAIL,
		                      '%m%' => self::KEY_NUMERO_CONTRAT,
		                      '%rs%' => self::KEY_RAISON_SOCIALE);

		$libelle = $format;

		foreach($format_index as $key => $item) {
		  if (isset($compte[$item])) {
		    $libelle = str_replace($key, $compte[$item], $libelle);
		  } else {
		    $libelle = str_replace($key, "", $libelle);
		  }
		}

        $libelle = preg_replace('/ +/', ' ', $libelle);

		return $libelle;
  	}
}