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
    const STATUS_NOUVEAU = 'NOUVEAU';
    const STATUS_INSCRIT = 'INSCRIT';
    const STATUS_MOT_DE_PASSE_OUBLIE = 'MOT_DE_PASSE_OUBLIE';
    
    /**
     *
     * @param string $mot_de_passe
     * @deprecated
     */
    public function setPasswordSSHA($mot_de_passe) 
    {
        mt_srand((double)microtime()*1000000);
        $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $hash = "{SSHA}" . base64_encode(pack("H*", sha1($mot_de_passe . $salt)) . $salt);
        $this->_set('mot_de_passe', $hash);
    }
    
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
    
    /**
     * 
     */
    protected function updateStatut() 
    {
       if (substr($this->mot_de_passe,0,6) == '{SSHA}') {
           $this->_set('statut', self::STATUS_INSCRIT);
       } elseif(substr($this->mot_de_passe,0,6) == '{TEXT}') {
           $this->_set('statut', self::STATUS_NOUVEAU);
       } elseif(substr($this->mot_de_passe,0,8) == '{OUBLIE}') {
           $this->_set('statut', self::STATUS_MOT_DE_PASSE_OUBLIE);
       } else {
           $this->_set('statut', null);
       }
    }
    
    /**
     *
     * @return string 
     */
    public function getStatus() 
    {
        $this->updateStatut();
        return $this->_get('statut');
    }
    
    /**
     * 
     */
    public function setStatus() 
    {
        throw new sfException("Compte status is not editable");
    }
    
    /**
     *
     * @return string 
     */
    public function getNom() 
    {
        return ' ';
    }
    
    /**
     *
     * @return string 
     */
    public function getGecos() 
    {
        return ',,'.$this->getNom().',';
    }
    
    /**
     *
     * @return string 
     */
    public function getAdresse() 
    {
        return ' ';
    }
    
    /**
     *
     * @return string 
     */
    public function getCodePostal() 
    {
        return ' ';
    }
    
    /**
     *
     * @return string 
     */
    public function getCommune() 
    {
        return  ' ';
    }
    
    /**
     * 
     */
    public function save() 
    {
        $this->updateStatut();
        parent::save();
    }
}