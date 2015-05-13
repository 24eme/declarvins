<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acVinComptePlugin model.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
abstract class acVinCompte extends BaseacVinCompte 
{
    const STATUT_NOUVEAU = 'NOUVEAU';
    const STATUT_INSCRIT = 'INSCRIT';
    const STATUT_INACTIF = 'INACTIF';
    const STATUT_ACTIF = 'ACTIF';
    const STATUT_MOT_DE_PASSE_OUBLIE = 'MOT_DE_PASSE_OUBLIE';
    
    
	const STATUT_FICTIF = 'FICTIF';
	const STATUT_ARCHIVE = 'ARCHIVE';
    const STATUT_ATTENTE = "EN_ATTENTE";
    const STATUT_VALIDE = "VALIDE";
    
	/**
     *
     * @param string $mot_de_passe
     */
    public function setMotDePasseSSHA($mot_de_passe) 
    {
        mt_srand((double)microtime()*1000000);
        $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $hash = "{SSHA}" . base64_encode(pack("H*", sha1($mot_de_passe . $salt)) . $salt);        
        $this->_set('mot_de_passe', $hash);
    }
    
	public function constructId() 
    {
        $this->set('_id', sfConfig::get('app_ac_vin_compte_couchdb_prefix').$this->login);
    }
    
	public static function getMotDePasseSSHA($mot_de_passe) 
    {
        mt_srand((double)microtime()*1000000);
        $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $hash = "{SSHA}" . base64_encode(pack("H*", sha1($mot_de_passe . $salt)) . $salt);        
        return $hash;
    }
    
	public static function compareMotDePasseSSHA($ldapMdp, $mdp) 
    {
		$ohash = base64_decode(str_replace('{SSHA}', '', $ldapMdp));
		$osalt = substr($ohash, 20);
		$ohash = substr($ohash, 0, 20);
		$nhash = pack("H*", sha1($mdp . $osalt));
    	return $ohash == $nhash;
    }
}