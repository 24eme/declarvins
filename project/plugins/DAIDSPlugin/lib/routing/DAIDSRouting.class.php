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
 * DRMRouting configuration.
 * 
 * @package    DAIDSRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class DAIDSRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        
        $r->prependRoute('daids_mon_espace', new EtablissementRoute('/daids/:identifiant', array('module' => 'daids', 
													'action' => 'monEspace'),
								  array('sf_method' => array('get','post')),
								  array('model' => 'Etablissement',
									'type' => "object")));
								  
        $r->prependRoute('daids_nouvelle', new DAIDSRoute('/daids/:identifiant/nouvelle/:periode_version', 
                                                array('module' => 'daids', 
                                                      'action' => 'nouvelle',
                                                	  'campagne' => null),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DAIDS',
                                                      'type' => 'object',
                                                      'creation' => true,
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => false)));

        $r->prependRoute('daids_delete', new DAIDSRoute('/daids/:identifiant/delete/:periode_version', 
                                                array('module' => 'daids', 
                                                      'action' => 'delete'),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DAIDS',
                                                      'type' => 'object',
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => false)));

        $r->prependRoute('daids_init', new DAIDSRoute('/daids/:identifiant/initialiser/:periode_version/:reinit_etape', 
                                                array('module' => 'daids', 
                                                      'action' => 'init',
                                                	  'reinit_etape' => null),
                                                array('sf_method' => array('get')),
                                                array('model' => 'DAIDS',
                                                      'type' => 'object',
                                                      'must_be_valid' => false,
                                                      'must_be_not_valid' => true)));
                                                
        $r->prependRoute('daids_informations', new DAIDSRoute('/daids/:identifiant/edition/:periode_version/informations', 
                                                          array('module' => 'daids', 
                                                                'action' => 'informations'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DAIDS',
                                                                'type' => 'object',
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));        
	      
	      $r->prependRoute('daids_validated', new DAIDSRoute('/daids/:identifiant/erreur/:periode_version/validee', 
                                                          array('module' => 'daids', 
                                                                'action' => 'validee'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DAIDS',
                                                                'type' => 'object',
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => false)));
	      
	      $r->prependRoute('daids_not_validated', new DAIDSRoute('/daids/:identifiant/erreur/:periode_version/non-validee', 
                                                          array('module' => 'daids', 
                                                                'action' => 'nonValidee'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DAIDS',
                                                                'type' => 'object',
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => false)));
        
        $r->prependRoute('daids_modif_infos', new DAIDSRoute('/daids/:identifiant/edition/:periode_version/modification-informations', 
                                                          array('module' => 'daids', 
                                                                'action' => 'modificationInfos'),
                                                          array('sf_method' => array('get','post')),
                                                          array('model' => 'DAIDS',
                                                                'type' => 'object',
                              									'must_be_valid' => false,
                              									'must_be_not_valid' => true)));  
        
        $r->prependRoute('daids_hamza', new EtablissementRoute('/daids/:identifiant/hamza', array('module' => 'daids', 
													'action' => 'hamza'),
								  array('sf_method' => array('get','post')),
								  array('model' => 'Etablissement',
									'type' => "object")));
        /*
         * RECAP
         */
        $r->prependRoute('daids_recap', new DAIDSLieuRoute('/daids/:identifiant/edition/:periode_version/recapitulatif/:certification',
                        array('module' => 'daids_recap',
                            'action' => 'index'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DAIDSLieu',
                            'type' => 'object',
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
        
        $r->prependRoute('daids_recap_redirect', new DAIDSRoute('/daids/:identifiant/edition/:periode_version/recapitulatif',
                        array('module' => 'daids_recap',
                            'action' => 'redirectIndex'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'DAIDS',
                            'type' => 'object',
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
                
        $r->prependRoute('daids_recap_lieu', new DAIDSLieuRoute('/daids/:identifiant/edition/:periode_version/recapitulatif/:certification/:genre/:appellation/:mention/:lieu',
                        array('module' => 'daids_recap',
                            'action' => 'lieu'),
                        array('sf_method' => array('get')),
                        array('model' => 'DAIDSAppellation',
                             'type' => 'object',
                            'must_be_valid' => false,
                            'must_be_not_valid' => true
                )));
    }

}
