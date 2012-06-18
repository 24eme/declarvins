<?php

class _CompteClient extends acVinCompteClient 
{        
   
    private $droits = array('administrateur' => 'Administrateur', 'operateur' => 'Opérateur');
   
  /**
   *
   * @return _CompteClient 
   */
  public static function getInstance() {
      return acCouchdbManager::getClient("_COMPTE");
  }
  
  
  public function getDroits()
  {
      return $this->droits;
  }
  
  public function findAll()
  {
  	return $this->getView('compte', 'all');
  }
	public function makeLibelle($datas) {
        $compteLibelle = '';
        if ($nom = $datas[1]) {
            $compteLibelle .= $nom.' ';
        }
        if ($prenom = $datas[2]) {
            $compteLibelle .= $prenom;
        }
        $compteLibelle .= ' ('.$datas[3];
        if ($email = $datas[4]) {
            if ($compteLibelle && $datas[3]) {
                $compteLibelle .= ' / ';
            }
            $compteLibelle .= $email;
        }
        if ($telephone = $datas[5]) {
            if ($compteLibelle) {
                $compteLibelle .= ' / ';
            }
            $compteLibelle .= $telephone;
        }
        $compteLibelle .= ') ';
        return trim($compteLibelle);
    }
}
