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
class acVinCompteTiers extends BaseacVinCompteTiers 
{

    protected $_tiers = null;
    protected $_duplicated = null;
    
    public function getNom() 
    {
        $nom = null;
        foreach ($this->tiers as $tiers) {
            if ($tiers->type == "Recoltant") {
                return $tiers->nom;
            } elseif (is_null($nom)) {
                $nom = $tiers->nom;
            }
        }
        return $nom;
    }

    /**
     *
     * @return array 
     */
    public function getTiersObject() 
    {
        if (is_null($this->_tiers)) {
	  		$this->_duplicated = null;
	  		$this->_tiers = array();
	  		foreach ($this->tiers as $tiers) {
	    		$this->_tiers[] = sfCouchdbManager::getClient()->retrieveDocumentById($tiers->id);
	  		}
        }
        return $this->_tiers;
    }

    /**
     *
     * @param string $hash
     * @return string 
     */
    public function getTiersField($hash, $exist = false) 
    {
        $value = null;
        foreach ($this->getTiersObject() as $tiers) {
            if ($exist && !$tiers->exist($hash)) {
                continue;
            }
            if ($tiers->type == 'Recoltant') {
                return $tiers->get($hash);
            } elseif (is_null($value)) {
                $value = $tiers->get($hash);
            }
            
        }
        return $value;
    }

    public function getGecos() 
    {
        $login = $this->getLogin();
        $gamma = $this->getTiersField('gamma', true);
        if($gamma && $gamma->num_cotisant) {
            $login = $gamma->num_cotisant;
        }
        return $login . ',' . $this->getTiersField('no_accises', true) . ',' . $this->getTiersField('intitule') . ' ' . $this->getTiersField('nom') . ',' . $this->getTiersField('exploitant/nom', true);
    }

    public function getAdresse() 
    {
        $value = $this->getTiersField('adresse');
        return $value ? $value : parent::getAdresse();
    }

    public function getCodePostal() 
    {
        $value = $this->getTiersField('code_postal');
        return $value ? $value : parent::getCodePostal();
    }

    public function getCommune() 
    {
        $value = $this->getTiersField('commune');
        return $value ? $value : parent::getCommune();
    }

    public function getDuplicatedTiers() 
    {
      if ($this->_duplicated)
      	return $this->_duplicated;

      $type = array();
      $this->_duplicated = array();
      foreach ($this->tiers as $id => $t) {
		if (isset($type[$t->type])) {
	  		$this->_duplicated[$t->id] = $t;
	  		$this->_duplicated[$type[$t->type]->id] = $type[$t->type];
		}
		$type[$t->type] = $t;
      }
      return $this->_duplicated;
    }
}