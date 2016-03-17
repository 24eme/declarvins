<?php
class CompteAllView extends acCouchdbView
{
	
	const KEY_INTERPRO = 0;
	const KEY_TYPE = 1;
	const KEY_STATUT = 2;
	
	const VALUE_NUMERO_CONTRAT = 0;
	const VALUE_NOM = 1;
	const VALUE_PRENOM = 2;
	const VALUE_LOGIN = 3;
	const VALUE_EMAIL = 4;
	const VALUE_RAISON_SOCIALE = 5;
	const VALUE_TELEPHONE = 6;
	const VALUE_OIOC = 7;
	const VALUE_CIEL = 8;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('compte', 'all', '_Compte');
    }

    public function findAll() 
    {
    	return $this->client->getView($this->design, $this->view);
  	}
  	
  	public function findBy($interpro, $type = null, $statut = null)
  	{
  		$startKey = array($interpro);
  		if ($type !== null) {
  			$startKey[] = $type;
	  		if ($statut !== null) {
	  			$startKey[] = $statut;
	  		}
  		}
  		$endKey = $startKey;
  		$endKey[] = array();
  		return $this->client->startkey($startKey)->endkey($endKey)->getView($this->design, $this->view);
  	}
  	
	public function formatComptes($comptes, $format = "%n% %p% %rs% (%l% %e% %m%)") 
	{
  		$comptes_format = array();
  		foreach($comptes->rows as $compte) {
  			$comptes_format[$compte->value[self::VALUE_NUMERO_CONTRAT]] = $this->formatCompte($compte->value, $format);
        }
        ksort($comptes_format);

        return $comptes_format;
  	}

  	protected function formatCompte($compte, $format = "%n% %p% %rs% (%l% %e% %m%)") {
  		
        $format_index = array('%n%' => self::VALUE_NOM,
		                      '%p%' => self::VALUE_PRENOM,
		                      '%l%' => self::VALUE_LOGIN,
		                      '%e%' => self::VALUE_EMAIL,
		                      '%m%' => self::VALUE_NUMERO_CONTRAT,
		                      '%rs%' => self::VALUE_RAISON_SOCIALE);

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

  	public function makeLibelle($datas) {
        $compteLibelle = '';
        if ($nom = $datas[self::VALUE_NOM]) {
            $compteLibelle .= $nom.' ';
        }
        if ($prenom = $datas[self::VALUE_PRENOM]) {
            $compteLibelle .= $prenom;
        }
        $compteLibelle .= ' ('.$datas[self::VALUE_LOGIN];
        if ($email = $datas[self::VALUE_EMAIL]) {
            if ($compteLibelle && $datas[self::VALUE_LOGIN]) {
                $compteLibelle .= ' / ';
            }
            $compteLibelle .= $email;
        }
        if ($telephone = $datas[self::VALUE_TELEPHONE]) {
            if ($compteLibelle) {
                $compteLibelle .= ' / ';
            }
            $compteLibelle .= $telephone;
        }
        $compteLibelle .= ') ';
        
        return trim($compteLibelle);
    }
}