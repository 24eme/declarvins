<?php

class VracRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('vrac_admin', new sfRoute('/vrac',
                                                    array('module' => 'vrac', 'action' => 'index'),
                                                    array('sf_method' => array('get'))
                                                        )); 
        
        $r->prependRoute('vrac_etablissement', new EtablissementRoute('/vrac/:identifiant', 
                                                        array('module' => 'vrac', 'action' => 'etablissement'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Etablissement', 'type' => 'object'))); 

        // $r->prependRoute('vrac_recherche', new sfRoute('/vrac/recherche', array('module' => 'vrac',
        //                                                     'action' => 'recherche')));
        // $r->prependRoute('vrac_recherche_soussigne', new sfRoute('/vrac/recherche-soussigne/:identifiant', array('module' => 'vrac',
        //                                                     'action' => 'rechercheSoussigne', 'identifiant' => null)));
        
        $r->prependRoute('vrac_nouveau', new VracRoute('/vrac/:identifiant/nouveau',  
                                                        array('module' => 'vrac', 'action' => 'nouveau'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Etablissement', 'type' => 'object'))); 

        $r->prependRoute('vrac_etape', new VracRoute('/vrac/:identifiant/:numero_contrat/etape/:step',
                                                        array('module' => 'vrac','action' => 'etape', 'step' => null),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_visualisation', new VracRoute('/vrac/:identifiant/:numero_contrat/visualisation',
                                                        array('module' => 'vrac','action' => 'visualisation'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object'))); 
        /*
         * BAZAR A MATHURIN ->
         */
        $r->prependRoute('vrac_nouveau_getinfos', new VracRoute('/vrac/getInfos',
                                                        array('module' => 'vrac','action' => 'getInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
       
        $r->prependRoute('vrac_soussigne_getinfos', new VracRoute('/vrac/:numero_contrat/getInfos',
                                                        array('module' => 'vrac','action' => 'getInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        
        
        $r->prependRoute('vrac_nouveau_modification', new VracRoute('/vrac/modification',
                                                        array('module' => 'vrac','action' => 'getModifications'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        
        $r->prependRoute('vrac_soussigne_modification', new VracRoute('/vrac/:numero_contrat/modification',
                                                        array('module' => 'vrac','action' => 'getModifications'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));

        $r->prependRoute('vrac_etablissement_informations', new VracRoute('/vrac/:identifiant/:numero_contrat/etape/:step/informations/:soussigne/:type',
                                                        array('module' => 'vrac','action' => 'setEtablissementInformations'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
 
//        $r->prependRoute('vrac_nouveau_modificationPost', new VracRoute('/vrac/modificationPost',
//                                                        array('module' => 'vrac','action' => 'modificationPost'),
//                                                        array('sf_method' => array('post')),
//                                                        array('model' => 'Vrac', 'type' => 'object')));
//        
//        $r->prependRoute('vrac_soussigne_modificationPost', new VracRoute('/vrac/:numero_contrat/modificationPost',
//                                                        array('module' => 'vrac','action' => 'modificationPost'),
//                                                        array('sf_method' => array('post')),
//                                                        array('model' => 'Vrac', 'type' => 'object')));
      
    }

}