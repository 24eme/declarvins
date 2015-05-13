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
 * acVinComptePlugin validator.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinValidatorCompteMotDePasseOublie extends sfValidatorBase 
{

    public function configure($options = array(), $messages = array()) 
    {
        $this->setMessage('invalid', 'Le numéro de CVI est incorrect.');
        $this->addMessage('invalid_status', "Vous n'avez pas encore créé votre compte. <br /> <br /> Pour ce faire munissez-vous de votre code d'accès reçu par courrier et cliquez sur le lien créer votre compte.");
    }

    protected function doClean($values) 
    {
        if(!$values['login']) {
            return array_merge($values);
        }
        
        $compte = acCouchdbManager::getClient('_Compte')->retrieveByLogin($values['login']);

        if (!$compte) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('mdp') => new sfValidatorError($this, 'invalid')));
        }

        if (!in_array($compte->getStatut(), array(_Compte::STATUT_INSCRIT, _Compte::STATUT_MOT_DE_PASSE_OUBLIE))) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('mdp') => new sfValidatorError($this, 'invalid_status')));
        }

        return array_merge($values, array('compte' => $compte));
    }
}

