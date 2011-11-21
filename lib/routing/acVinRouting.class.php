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
 * acVinComptePlugin configuration.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    $r->prependRoute('ac_vin_login', new sfRoute('/', array('module' => 'acVinCompte', 'action' => 'acVinCompteLogin')));
    $r->prependRoute('ac_vin_logout', new sfRoute('/logout', array('module' => 'acVinCompte', 'action' => 'acVinCompteLogout')));
    $r->prependRoute('ac_vin_compte', new sfRoute('/compte', array('module' => 'acVinCompte', 'action' => 'acVinCompteFirst')));
    $r->prependRoute('ac_vin_compte_creation', new sfRoute('/compte/creation', array('module' => 'acVinCompte', 'action' => 'acVinCompteCreation')));
    $r->prependRoute('ac_vin_compte_modification_oublie', new sfRoute('/compte/mot_de_passe_oublie', array('module' => 'acVinCompte', 'action' => 'acVinCompteModificationOublie')));
    $r->prependRoute('ac_vin_compte_modification', new sfRoute('/mon_compte', array('module' => 'acVinCompte', 'action' => 'acVinCompteModification')));
    $r->prependRoute('ac_vin_compte_mot_de_passe_oublie_login', new sfRoute('/mot_de_passe_oublie/login/:login/:mdp', array('module' => 'acVinCompte', 'action' => 'acVinCompteMotDePasseOublieLogin')));
    $r->prependRoute('ac_vin_compte_mot_de_passe_oublie', new sfRoute('/mot_de_passe_oublie', array('module' => 'acVinCompte', 'action' => 'acVinCompteMotDePasseOublie')));
    $r->prependRoute('ac_vin_compte_mot_de_passe_oublie_confirm', new sfRoute('/mot_de_passe_oublie/confirm', array('module' => 'acVinCompte', 'action' => 'acVinCompteMotDePasseOublieConfirm')));
  }
}