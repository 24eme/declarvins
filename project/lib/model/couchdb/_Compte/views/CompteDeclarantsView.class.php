<?php
class CompteDeclarantsView extends acCouchdbView
{
	const KEY_INTERPRO = 0;
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

    public function findByInterpro($interpro) 
    {
    	return $this->client
                                 ->startkey(array($interpro))
                                 ->endkey(array($interpro, array()))
                                 ->getView($this->design, $this->view);
  	}
  	
	public function formatComptes($interpro, $format = "%n% %p% %r% (%l% %e% %m%)") 
	{
  		$comptes_format = array();
  		$comptes = $this->findByInterpro($interpro);
  		foreach($comptes->rows as $compte) {
  			$comptes_format[$compte->key[self::KEY_NUMERO_CONTRAT]] = $this->formatCompte($compte->key, $format);
        }
        ksort($comptes_format);

        return $comptes_format;
  	}

  	protected function formatCompte($compte, $format = "%n% %p% %r% (%l% %e% %m%)") {
  		
        $format_index = array('%n%' => self::KEY_NOM,
		                      '%p%' => self::KEY_PRENOM,
		                      '%r%' => self::KEY_RAISON_SOCIALE,
		                      '%l%' => self::KEY_LOGIN,
		                      '%e%' => self::KEY_EMAIL,
		                      '%m%' => self::KEY_NUMERO_CONTRAT);

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