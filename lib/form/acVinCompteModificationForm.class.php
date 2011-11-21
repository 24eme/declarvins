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
 * acVinComptePlugin form.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinCompteModificationForm extends CompteForm 
{
    
    /**
     * 
     */
    protected function checkCompte() 
    {
        parent::checkCompte();
        if ($this->_compte->getStatus() != _Compte::STATUS_INSCRIT) {
            throw new sfException("compte must be status : INSCRIT");
        }
    }
    
    /**
     * 
     */
    public function configure() 
    {
        parent::configure();
        $this->getValidator('mdp1')->setOption('required', false);
        $this->getValidator('mdp2')->setOption('required', false);
    }
    
    /**
     *
     * @return _Compte 
     */
    public function save() 
    {
        if ($this->isValid()) {
            if ($this->getValue('mdp1')) {
                $this->_compte->setPasswordSSHA($this->getValue('mdp1'));
            }
            $this->_compte->email = $this->getValue('email');
            $this->_compte->save();
            $this->_compte->updateLdap();
        } else {
            throw new sfException("form must be valid");
        }
        
        return $this->_compte;
    }
}