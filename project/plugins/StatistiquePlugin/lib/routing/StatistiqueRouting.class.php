<?php

/* This file is part of the DAIDSPlugin package.
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
 * StatistiqueRouting configuration.
 * 
 * @package    StatistiqueRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class StatistiqueRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        $r->prependRoute('statistiques_bilan_drm', new sfRoute('/admin/statistiques/bilan-drm', array('module' => 'statistique', 'action' => 'bilanDrm')));
        $r->prependRoute('statistiques', new sfRoute('/admin/:type/statistiques/', array('module' => 'statistique', 'action' => 'statistiques', 'type' => null)));
    }

}
