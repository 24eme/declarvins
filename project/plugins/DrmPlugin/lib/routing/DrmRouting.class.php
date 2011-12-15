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
 * DrmRouting configuration.
 * 
 * @package    DrmRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class DrmRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('drm_recap', new DrmRecapAppellationRoute('/drm/recapitulatif/:label',
                        array('module' => 'drm_recap',
                            'action' => 'index'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_appellation_ajout_ajax', new DrmRecapLabelRoute('/drm/recapitulatif-appellation-ajout/:label',
                        array('module' => 'drm_recap',
                            'action' => 'appellationAjoutAjax'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMLabel',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_recap_appellation', new DrmRecapAppellationRoute('/drm/recapitulatif/:label/:appellation',
                        array('module' => 'drm_recap',
                            'action' => 'appellation'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_ajout', new DrmRecapAppellationRoute('/drm/recapitulatif/:label/:appellation/ajout',
                        array('module' => 'drm_recap',
                            'action' => 'ajout'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_update', new DrmRecapDetailRoute('/drm/recapitulatif/:label/:appellation/update/:couleur/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'update',
                            'detail' => null),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_mouvements_generaux_produit_update', new DrmMouvementsGenerauxProduitUpdateRoute('/drm/mouvements-generaux/:certification/:appellation/update/:indice',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'saveFormAjax'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMProduit',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_mouvements_generaux', new sfRoute('/drm/mouvements-generaux', array('module' => 'drm_mouvements_generaux', 'action' => 'index')));
        $r->prependRoute('drm_mouvements_generaux_product_form', new sfRoute('/drm/mouvements-generaux/product-form', array('module' => 'drm_mouvements_generaux', 'action' => 'productFormAjax')));
        $r->prependRoute('drm_mouvements_generaux_save', new sfRoute('/drm/mouvements-generaux/save', array('module' => 'drm_mouvements_generaux', 'action' => 'saveFormAjax')));
    }

}