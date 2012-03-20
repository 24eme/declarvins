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

        $r->prependRoute('drm_historique', new sfRoute('/drm/historique/:annee', array('module' => 'drm', 
                                                                                       'action' => 'historique', 
                                                                                       'annee' => null)));

        $r->prependRoute('drm_nouvelle', new DrmLightRoute('/drm/nouvelle', 
                                                array('module' => 'drm', 
                                                      'action' => 'nouvelle'),
                                                array('sf_method' => array('get'))));

        $r->prependRoute('drm_init', new DrmLightRoute('/drm/initialiser/:campagne_rectificative', 
                                                array('module' => 'drm', 
                                                      'action' => 'init'),
                                                array('sf_method' => array('get'))));

        $r->prependRoute('drm_rectificative', new DrmLightRoute('/drm/rectifier/:campagne_rectificative', 
                                                          array('module' => 'drm', 
                                                               'action' => 'rectificative')));

        $r->prependRoute('drm_informations', new DrmRoute('/drm-edition/:campagne_rectificative/informations', 
                                                          array('module' => 'drm', 
                                                                'action' => 'informations'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object')));

        $r->prependRoute('drm_declaratif', new DrmRoute('/drm-edition/:campagne_rectificative/declaratif', 
                                                          array('module' => 'drm', 
                                                                'action' => 'declaratif'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object')));

        $r->prependRoute('drm_validation', new DrmRoute('/drm-edition/:campagne_rectificative/validation', 
                                                          array('module' => 'drm', 
                                                                'action' => 'validation'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object')));

        $r->prependRoute('drm_visualisation', new DrmLightRoute('/drm/visualisation/:campagne_rectificative', 
                                                          array('module' => 'drm', 
                                                                'action' => 'visualisation'),
                                                          array('sf_method' => array('get'))));

        $r->prependRoute('drm_pdf', new DrmLightRoute('/drm/pdf/:campagne_rectificative.:format', 
                                                          array('module' => 'drm', 
                                                                'action' => 'pdf',
                                                                'format' => 'pdf'),
                                                          array('sf_method' => array('get'), 'format' => '(html|pdf)')));

        $r->prependRoute('drm_mouvements_generaux', new DrmRoute('/drm-edition/:campagne_rectificative/mouvements-generaux', 
                                                          array('module' => 'drm_mouvements_generaux', 
                                                                'action' => 'index'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object')));

        $r->prependRoute('drm_mouvements_generaux_produit_update', new DrmProduitRoute('/drm-edition/:campagne_rectificative/mouvements-generaux/:certification/:appellation/update/:indice',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'updateAjax'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMProduit',
                              'type' => 'object')));

        $r->prependRoute('drm_mouvements_generaux_produit_delete', new DrmProduitRoute('/drm-edition/:campagne_rectificative/mouvements-generaux/:certification/:appellation/delete/:indice',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'deleteAjax'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMProduit',
                              'type' => 'object')));  

        $r->prependRoute('drm_mouvements_generaux_product_form', new DrmRoute('/drm-edition/:campagne_rectificative/mouvements-generaux/ajout/:certification',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'ajoutAjax'),
                        array('sf_method' => array('get','post')),
                        array('model' => 'DRMProduit',
                              'type' => 'object')));

        $r->prependRoute('drm_recap', new DrmAppellationRoute('/drm-edition/:campagne_rectificative/recapitulatif/:certification',
                        array('module' => 'drm_recap',
                            'action' => 'index'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_appellation_ajout_ajax', new DrmCertificationRoute('/drm-edition/:campagne_rectificative/recapitulatif-appellation-ajout/:certification',
                        array('module' => 'drm_recap',
                            'action' => 'appellationAjoutAjax'),
                        array('sf_method' => array('get','post')),
                        array('model' => 'DRMCertification',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_recap_appellation', new DrmAppellationRoute('/drm-edition/:campagne_rectificative/recapitulatif/:certification/:appellation',
                        array('module' => 'drm_recap',
                            'action' => 'appellation'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                             'type' => 'object'
                )));

        $r->prependRoute('drm_recap_detail', new DrmDetailRoute('/drm-edition/:campagne_rectificative/recapitulatif/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'detail'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_ajout_ajax', new DrmAppellationRoute('/drm-edition/:campagne_rectificative/recapitulatif/:certification/:appellation/ajout-ajax',
                        array('module' => 'drm_recap',
                            'action' => 'ajoutAjax'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object'
                )));
        
        $r->prependRoute('drm_recap_update', new DrmDetailRoute('/drm-edition/:campagne_rectificative/recapitulatif/update/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'update'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_vrac', new DrmRoute('/drm-edition/:campagne_rectificative/vrac', 
                                                          array('module' => 'drm_vrac', 
                                                                'action' => 'index'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object')));

        $r->prependRoute('drm_vrac_ajout_contrat', new DrmDetailRoute('/drm-edition/:campagne_rectificative/vrac/contrat/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/ajout/:detail',
                        array('module' => 'drm_vrac',
                            'action' => 'nouveauContrat',
                            'detail' => null),
                        array('sf_method' => array('post', 'get')),
                        array('model' => 'DRMDetail',
                            'type' => 'object'
                )));
        $r->prependRoute('drm_vrac_update_volume', new DRMVracDetailRoute('/drm-edition/:campagne_rectificative/vrac/update/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail/volume/:contrat',
                        array('module' => 'drm_vrac',
                            'action' => 'updateVolume'),
                        array('sf_method' => array('post')),
                        array('model' => 'acCouchdbJson',
                            'type' => 'object'
                )));

        $r->prependRoute('drm_delete_vrac', new DRMVracDetailRoute('/drm-edition/:campagne_rectificative/vrac/update/:certification/:appellation/:lieu/:couleur/:cepage/:millesime/:detail/delete/:contrat',
                        array('module' => 'drm_vrac',
                            'action' => 'deleteVrac'),
                        array('sf_method' => array('post', 'get')),
                        array('model' => 'acCouchdbJson',
                            'type' => 'object'
                )));
        
    }

}