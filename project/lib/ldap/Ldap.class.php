<?php
class Ldap {

    private static $groupe2gid = array('declarant' => 1000, 'admin' => 1001, 'exterieur' => 1002);
    private static $gid2groupe = array('1000' => 'declarant', '1001' => 'admin', '1002' => 'exterieur');

    protected $serveur;
    protected $dn;
    protected $dc;
    protected $pass;

    /**
     * 
     */
    public function __construct(){
             $this->serveur = sfConfig::get('app_ldap_serveur');
             $this->dn = sfConfig::get('app_ldap_dn');
             $this->dc = sfConfig::get('app_ldap_dc');
             $this->pass = sfConfig::get('app_ldap_pass');
    }

    /**
     *
     * @return bool 
     */
    public function connect() {
        $con = ldap_connect($this->serveur);
        ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
        if ($con && ldap_bind($con, $this->dn, $this->pass)) {
            return $con;
        } else {
            return false;
        }
    }
    
    /**
     *
     * @param _Compte $compte
     * @return bool 
     */
    public function add($compte) {
        $con = $this->connect();
        if($con) {
            $add = ldap_add($con, 
                        'uid='.$compte->login.',ou=People,'.$this->dc,
                        $this->info($compte));
            ldap_unbind($con);
            return $add;
        }
        return false;
    }
    
    /**
     *
     * @param _Compte $compte
     * @return bool 
     */
    public function update($compte) {
        $con = $this->connect();
        if($con) {
            $update = ldap_modify($con, 
                            'uid='.$compte->login.',ou=People,'.$this->dc, 
                            $this->info($compte));
            ldap_unbind($con);
            return $update;
        }
        return false;
    }

    public function updateOrAdd($compte) {
      if ($this->exist($compte))
	  return $this->update($compte);
      return $this->add($compte);
    }

    /**
     *
     * @param _Compte $compte
     * @return bool 
     */
    public function delete($compte) {
        $con = $this->connect();
        if($con) {
            $delete = ldap_delete($con, 'uid='.$compte->login.',ou=People,'.$this->dc);
            ldap_unbind($con);
            return $delete;
        }else{
            return false;
        }
    }

    /**
     *
     * @param _Compte $compte
     * @return bool 
     */
    public function exist($compte){
        $con = $this->connect();
        if($con) {
            $search = ldap_search($con, 'ou=People,'.$this->dc, 'uid='.$compte->login);
            if($search){
                $count = ldap_count_entries($con, $search);
                if($count > 0) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     *
     * @param _Compte $compte
     * @return array 
     */
    protected function info($compte) {
      $info = array();
      $info['uid']           = $compte->login;
      $info['sn']            = $compte->getNom(); 
      $info['cn']            = $compte->getNom(); 
      $info['objectClass'][0]   = 'top';
      $info['objectClass'][1]   = 'person';
      $info['objectClass'][2]   = 'posixAccount';
      $info['objectClass'][3]   = 'inetOrgPerson';
      $info['userPassword']  = $compte->mot_de_passe;
      $info['loginShell']    = '/bin/bash';
      $info['uidNumber']     = '1000';
      $info['gidNumber']     = $this->getGid($compte);
      $info['homeDirectory'] = '/home/'.$compte->login;
      $info['gecos']         = $compte->getGecos();
      $info['mail']          = $compte->email;
      //      $info['postalAddress'] = $compte->getAdresse();
      //      $info['postalCode']    = $compte->getCodePostal();
      //      $info['l']             = $compte->getCommune();
      return $info;
    }
    
    /**
     *
     * @param _Compte $compte
     * @return string 
     */
    protected function getGid($compte) {
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
