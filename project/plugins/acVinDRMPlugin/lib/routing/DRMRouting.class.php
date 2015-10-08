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
 * DRMRouting configuration.
 * 
 * @package    DRMRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class DRMRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        
	      $r->prependRoute('drm_notice', new sfRoute('/drm/document/notice', array('module' => 'drm', 
									'action' => 'downloadNotice')));
	      
	      $r->prependRoute('drm_validated', new DRMRoute('/drm/:identifiant/erreur/:periode_version/validee', 
                                                          array('module' => 'drm', 
                                                                'action' => 'validee'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => false)));
	      
	      $r->prependRoute('drm_not_validated', new DRMRoute('/drm/:identifiant/erreur/:periode_version/non-validee', 
                                                          array('module' => 'drm', 
                                                                'action' => 'nonValidee'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => false)));
        
        $r->prependRoute('drm_mon_espace', new EtablissementRoute('/drm/:identifiant', array('module' => 'drm', 
													'action' => 'monEspace'),
								  array('sf_method' => array('get','post')),
								  array('model' => 'Etablissement',
									'type' => "object")));

        $r->prependRoute('drm_historique', new EtablissementRoute('/drm/:identifiant/historique/:campagne', array('module' => 'drm', 
														  'action' => 'historique', 
														  'campagne' => null),
								  array('sf_method' => array('get','post')),
								  array('model' => 'Etablissement',
									'type' => 'object')));


        $r->prependRoute('drm_nouvelle', new DRMRoute('/drm/:identifiant/nouvelle/:periode_version', 
                                                array('module' => 'drm', 
                                                      'action' => 'nouvelle',
                                                	  'campagne' => null),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DRM',
                                                      'type' => 'object',
                                                      'creation' => true,
                            						  'no_archive' => true,
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => false)));

        $r->prependRoute('drm_delete', new DRMRoute('/drm/:identifiant/delete/:periode_version', 
                                                array('module' => 'drm', 
                                                      'action' => 'delete'),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DRM',
                                                      'type' => 'object',
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => false)));

        $r->prependRoute('drm_delete_one', new DRMRoute('/drm/:identifiant/delete-one/:periode_version', 
                                                array('module' => 'drm', 
                                                      'action' => 'deleteOne'),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DRM',
                                                      'type' => 'object',
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => false)));

        $r->prependRoute('drm_init', new DRMRoute('/drm/:identifiant/initialiser/:periode_version/:reinit_etape', 
                                                array('module' => 'drm', 
                                                      'action' => 'init',
                                                	  'reinit_etape' => null),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DRM',
                                                      'type' => 'object',
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => true)));

        $r->prependRoute('drm_rectificative', new DRMRoute('/drm/:identifiant/rectifier/:periode_version', 
                                                          array('module' => 'drm', 
                                                               'action' => 'rectificative'),
                                                          array(),
                                                		  array('model' => 'DRM',
                                                            'type' => 'object',
                            						  		'no_archive' => true,
                                                            'must_be_valid' => true, 
                                                            'must_be_not_valid' => false)));

        $r->prependRoute('drm_modificative', new DRMRoute('/drm/:identifiant/modifier/:periode_version', 
                                                          array('module' => 'drm', 
                                                               'action' => 'modificative'),
                                                          array(),
                                                      array('model' => 'DRM',
                                                            'type' => 'object',
                            						  		'no_archive' => true,
                                                            'must_be_valid' => true, 
                                                            'must_be_not_valid' => false)));

        $r->prependRoute('drm_informations', new DRMRoute('/drm/:identifiant/edition/:periode_version/informations', 
                                                          array('module' => 'drm', 
                                                                'action' => 'informations'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));
        
        $r->prependRoute('drm_modif_infos', new DRMRoute('/drm/:identifiant/edition/:periode_version/modification-informations', 
                                                          array('module' => 'drm', 
                                                                'action' => 'modificationInfos'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

        $r->prependRoute('drm_stock_debut_mois', new DRMRoute('/drm/:identifiant/edition/:periode_version/stock', 
                                                          array('module' => 'drm', 
                                                                'action' => 'stock'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

        $r->prependRoute('drm_declaratif', new DRMRoute('/drm/:identifiant/edition/:periode_version/declaratif', 
                                                          array('module' => 'drm', 
                                                                'action' => 'declaratif'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));
        

        $r->prependRoute('drm_declaratif_frequence_form', new DRMRoute('/drm/:identifiant/edition/:periode_version/declaratif/frequence-paiement',
                                                          array('module' => 'drm', 
                                                                'action' => 'paiementFrequenceFormAjax'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

        $r->prependRoute('drm_validation', new DRMRoute('/drm/:identifiant/edition/:periode_version/validation', 
                                                          array('module' => 'drm', 
                                                                'action' => 'validation'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

        $r->prependRoute('drm_payer_report', new DRMRoute('/drm/:identifiant/edition/:periode_version/payement-report', 
                                                          array('module' => 'drm', 
                                                                'action' => 'payerReport'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

        $r->prependRoute('drm_show_error', new DRMRoute('/drm/:identifiant/edition/:periode_version/voir-erreur/:type/:identifiant_controle', 
                                                          array('module' => 'drm', 
                                                                'action' => 'showError'),
                                                          array('sf_method' => array('get')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

        $r->prependRoute('drm_visualisation', new DRMRoute('/drm/:identifiant/visualisation/:periode_version/:hide_rectificative', 
                                                          array('module' => 'drm', 
                                                                'action' => 'visualisation',
                                                          		'hide_rectificative' => null),
                                                          array('sf_method' => array('get')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                                                          'must_be_valid' => true,
                                                          'must_be_not_valid' => false)));

        $r->prependRoute('drm_pdf', new DRMRoute('/drm/:identifiant/pdf/:periode_version.:format', 
                                                          array('module' => 'drm', 
                                                                'action' => 'pdf',
                                                                'format' => 'pdf'),
                                                          array('sf_method' => array('get'), 'format' => '(html|pdf)'),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                                                                'must_be_valid' => false,
                              									                'must_be_not_valid' => false)));

        $r->prependRoute('drm_mouvements_generaux', new DRMRoute('/drm/:identifiant/edition/:periode_version/mouvements-generaux', 
                                                          array('module' => 'drm_mouvements_generaux', 
                                                                'action' => 'index'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                            						  			'no_archive' => true,
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));

                        
        $r->prependRoute('drm_mouvements_generaux_stock_epuise', new DRMRoute('/drm/:identifiant/edition/:periode_version/mouvements-generaux/stock-epuise',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'stockEpuise'),
                        array('sf_method' => array('post', 'get')),
                        array('model' => 'DRM',
                              'type' => 'object',
                              'no_archive' => true,
                              'must_be_valid' => false,
                              'must_be_not_valid' => true)));

        $r->prependRoute('drm_mouvements_generaux_produit_delete', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/mouvements-generaux/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail/delete',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'delete'),
                        array('sf_method' => array('post', 'get')),
                        array('model' => 'DRMProduit',
                              'type' => 'object',
                              'no_archive' => true,
                              'must_be_valid' => false,
                              'must_be_not_valid' => true)));  

        $r->prependRoute('drm_mouvements_generaux_product_ajout', new DRMCertificationRoute('/drm/:identifiant/edition/:periode_version/mouvements-generaux/ajout/:certification',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'ajoutAjax'),
                        array('sf_method' => array('get','post')),
                        array('model' => 'DRMCertification',
                              'type' => 'object',
                              'no_archive' => true,
                              'add_noeud' => true,
                              'must_be_valid' => false,
                              'must_be_not_valid' => true)));

        $r->prependRoute('drm_mouvements_generaux_product_add', new DRMCertificationRoute('/drm/:identifiant/edition/:periode_version/mouvements-generaux/add/:certification',
                        array('module' => 'drm_mouvements_generaux',
                            'action' => 'add'),
                        array('sf_method' => array('get','post')),
                        array('model' => 'DRMCertification',
                              'type' => 'object',
                              'no_archive' => true,
                              'add_noeud' => true,
                              'must_be_valid' => false,
                              'must_be_not_valid' => true)));

        $r->prependRoute('drm_recap', new DRMLieuRoute('/drm/:identifiant/edition/:periode_version/recapitulatif/:certification',
                        array('module' => 'drm_recap',
                            'action' => 'index'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRMLieu',
                            'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
        
        $r->prependRoute('drm_recap_redirect', new DRMRoute('/drm/:identifiant/edition/:periode_version/recapitulatif',
                        array('module' => 'drm_recap',
                            'action' => 'redirectIndex'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRM',
                            'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
        
        $r->prependRoute('drm_recap_redirect_last', new DRMRoute('/drm/:identifiant/edition/:periode_version/dernier/recapitulatif',
                        array('module' => 'drm_recap',
                            'action' => 'redirectLast'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRM',
                            'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
        
        $r->prependRoute('drm_recap_lieu_ajout_ajax', new DRMCertificationRoute('/drm/:identifiant/edition/:periode_version/recapitulatif-appellation-ajout/:certification',
                        array('module' => 'drm_recap',
                            'action' => 'lieuAjoutAjax'),
                        array('sf_method' => array('get','post')),
                        array('model' => 'DRMCertification',
                            'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));

        $r->prependRoute('drm_recap_lieu', new DRMLieuRoute('/drm/:identifiant/edition/:periode_version/recapitulatif/:certification/:genre/:appellation/:mention/:lieu',
                        array('module' => 'drm_recap',
                            'action' => 'lieu'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMAppellation',
                             'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));

        $r->prependRoute('drm_recap_detail', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/recapitulatif/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'detail'),
                        array('sf_method' => array('get')),
                        array('model' => 'DRMDetail',
                            'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
        
        $r->prependRoute('drm_recap_ajout_ajax', new DRMLieuRoute('/drm/:identifiant/edition/:periode_version/recapitulatif/:certification/:genre/:appellation/:mention/:lieu/ajout-ajax',
                        array('module' => 'drm_recap',
                            'action' => 'ajoutAjax'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DRMAppellation',
                            'type' => 'object',
                            'no_archive' => true,
                            'add_noeud' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
        
        $r->prependRoute('drm_recap_update', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/recapitulatif/update/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail',
                        array('module' => 'drm_recap',
                            'action' => 'update'),
                        array('sf_method' => array('post')),
                        array('model' => 'DRMDetail',
                              'type' => 'object',
                            'no_archive' => true,
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));

        $r->prependRoute('drm_vrac', new DRMRoute('/drm/:identifiant/edition/:periode_version/vrac', 
                                                          array('module' => 'drm_vrac', 
                                                                'action' => 'index'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DRM',
                                                                'type' => 'object',
                              									'no_archive' => true,
									                            'must_be_valid' => false,
									                            'must_be_not_valid' => true)));      
    }

}
