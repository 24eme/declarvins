<?php
class Ldap extends acVinLdap {

    private static $groupe2gid = array('declarant' => 1000, 'admin' => 1001, 'exterieur' => 1002);
    

    public function saveCompte($compte) 
    {
      return $this->save($compte->login, $this->info($compte));
    }

    /**
     *
     * @param _Compte $compte
     * @return bool 
     */
    public function deleteCompte($compte) {
        return $this->delete($compte->login);
    }
    
    /**
     *
     * @param _Compte $compte
     * @return array 
     */
    protected function info($compte) 
    {
      $info = array();
      $info['uid']              = $compte->login;
      $info['sn']               = $compte->getNom(); 
      $info['cn']               = $compte->getNom(); 
      $info['objectClass'][0]   = 'top';
      $info['objectClass'][1]   = 'person';
      $info['objectClass'][2]   = 'posixAccount';
      $info['objectClass'][3]   = 'inetOrgPerson';
      $info['userPassword']     = $compte->mot_de_passe;
      $info['loginShell']       = '/bin/bash';
      $info['uidNumber']        = '1000';
      $info['gidNumber']        = $this->getGid($compte);
      $info['homeDirectory']    = '/home/'.$compte->login;
      $info['gecos']            = $compte->getGecos();
      $info['mail']             = $compte->email;
      return $info;
    }
    
    /**
     *
     * @param _Compte $compte
     * @return string 
     */
    protected function getGid($compte) 
    {
        if ($compte->type == 'CompteTiers') {
            return self::$groupe2gid["declarant"];
        } elseif($compte->type == 'CompteProxy') {
            return $this->getGid($compte->getCompteReferenceObject());
        } elseif($compte->type == 'CompteVirtuel') {
            if (in_array("admin", $compte->droits->toArray())) {
                return self::$groupe2gid["admin"];
            } else {
                return self::$groupe2gid["exterieur"];
            }
        } else {
            return self::$groupe2gid["declarant"];
        }
    }
}
