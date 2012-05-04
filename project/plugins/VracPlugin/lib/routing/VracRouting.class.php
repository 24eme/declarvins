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
 * VracRouting configuration.
 * 
 * @package    VracRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class VracRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        $r->prependRoute('vrac', new sfRoute('/vrac', array('module' => 'vrac',
                    'action' => 'list')));
        $r->prependRoute('vrac_historique', new sfRoute('/vrac/historique/:campagne', array('module' => 'vrac', 
                                                                                       'action' => 'historique', 
                                                                                       'campagne' => null)));
        $r->prependRoute('vrac_switch', new sfRoute('/vrac/:id/switch/:campagne', array('module' => 'vrac', 
                                                                                       'action' => 'switch', 
        																			   'id' => null,
                                                                                       'campagne' => null)));
    }

}