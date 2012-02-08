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

        $r->prependRoute('drm_mon_espace', new sfRoute('/drm/mon-espace', array('module' => 'drm', 
                                                                                'action' => 'monEspace')));

        $r->prependRoute('drm_init', new sfRoute('/drm/init', array('module' => 'drm', 
                                                                    'action' => 'init')));

        $r->prependRoute('drm_informations', new sfRoute('/drm/informations', array('module' => 'drm', 
                                                                                    'action' => 'informations')));

        $r->prependRoute('drm_historique', new sfRoute('/drm/historique/:annee', array('module' => 'drm', 
                                                                                       'action' => 'historique', 
                                                                                       'annee' => null)));

        $r->prependRoute('drm_validation', new sfRoute('/drm/validation', array('module' => 'drm', 
                                                                                'action' => 'validation')));

        $r->prependRoute('drm_mouvements_generaux_produit_update', new DrmProduitRoute('/drm/mouvements-generaux/:certification/:appellation/update/:indice',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'saveFormAjax'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMProduit',
                            'type' => 'object'
                )));
        $r->prependRoute('drm_mouvements_generaux_produit_delete', new DrmProduitRoute('/drm/mouvements-generaux/:certification/:appellation/delete/:indice',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'deleteAjax'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMProduit',
                            'type' => 'object'
                )));  

        $r->prependRoute('drm_mouvements_generaux', new sfRoute('/drm/mouvements-generaux', array('module' => 'drm_mouvements_generaux', 'action' => 'index')));

        $r->prependRoute('drm_mouvements_generaux_product_form', new sfRoute('/drm/mouvements-generaux/ajout/:certification',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'productFormAjax'),
                        array('sf_method' => array('get','post'))
                ));

        $r->prependRoute('drm_recap', new DrmAppellationRoute('/drm/recapitulatif/:certification',
                        array('module' => 'drm_recap',
                            'action' => 'index'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_appellation_ajout_ajax', new DrmCertificationRoute('/drm/recapitulatif-appellation-ajout/:certification',
                        array('module' => 'drm_recap',
                            'action' => 'appellationAjoutAjax'),
                        array('sf_method' => array('get','post')),
                        array('model' => 'DRMCertification',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_recap_appellation', new DrmAppellationRoute('/drm/recapitulatif/:certification/:appellation',
                        array('module' => 'drm_recap',
                            'action' => 'appellation'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                             'type' => 'object'
                )));

        $r->prependRoute('drm_recap_detail', new DrmDetailRoute('/drm/recapitulatif/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'detail'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));
        
        /*$r->prependRoute('drm_recap_ajout_ajax', new DrmAppellationRoute('/drm/recapitulatif/:certification/:appellation/ajout-ajax',
                        array('module' => 'drm_recap',
                            'action' => 'ajoutAjax'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));*/
        
        $r->prependRoute('drm_recap_update', new DrmDetailRoute('/drm/recapitulatif/update/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'update'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_vrac', new sfRoute('/drm/vrac', array('module' => 'drm_vrac', 'action' => 'index')));

        $r->prependRoute('drm_vrac_ajout_contrat', new DrmDetailRoute('/drm/vrac/contrat/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/ajout/:detail',
                        array('module' => 'drm_vrac',
                            'action' => 'nouveauContrat',
                            'detail' => null),
                        array('sf_method' => array('post', 'get')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));
        $r->prependRoute('drm_vrac_update_volume', new VracDetailContratRoute('/drm/vrac/update/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail/volume/:contrat',
                        array('module' => 'drm_vrac',
                            'action' => 'updateVolume'),
                        array('sf_method' => array('post')),
                        array('model' => 'acCouchdbJson',
                            'type' => 'object'
                )));
        
    }

}