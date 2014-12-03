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

        $r->prependRoute('vrac_admin', new sfRoute('/listing/vrac/:statut',
                                                    array('module' => 'vrac', 'action' => 'index', 'statut' => null),
                                                    array('sf_method' => array('get'))
                                                        )); 
        
        $r->prependRoute('vrac_etablissement', new EtablissementRoute('/vrac/:identifiant/:statut', 
                                                        array('module' => 'vrac', 'action' => 'etablissement', 'statut' => null),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Etablissement', 'type' => 'object'))); 
        
        $r->prependRoute('vrac_valide_admin', new sfRoute('/vrac-erreur',
                                                    array('module' => 'vrac', 'action' => 'valideAdmin'),
                                                    array('sf_method' => array('get'))
                                                        )); 
        
        $r->prependRoute('vrac_valide', new EtablissementRoute('/vrac-erreur/:identifiant', 
                                                        array('module' => 'vrac', 'action' => 'valide'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Etablissement', 'type' => 'object'))); 

        $r->prependRoute('vrac_nouveau', new VracRoute('/vrac/:identifiant/nouveau',  
                                                        array('module' => 'vrac', 'action' => 'nouveau'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Etablissement', 'type' => 'object', 'no_archive' => true)));
                                                         
        $r->prependRoute('vrac_supprimer', new VracRoute('/vrac/:identifiant/:numero_contrat/supprimer',  
                                                        array('module' => 'vrac', 'action' => 'supprimer'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true)));
        $r->prependRoute('vrac_edition', new VracRoute('/vrac/:identifiant/:numero_contrat/edition',
                                                        array('module' => 'vrac','action' => 'edition'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true)));
        $r->prependRoute('vrac_etape', new VracRoute('/vrac/:identifiant/:numero_contrat/etape/:step',
                                                        array('module' => 'vrac','action' => 'etape', 'step' => null),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true)));
        $r->prependRoute('vrac_visualisation', new VracRoute('/vrac/:identifiant/:numero_contrat/visualisation',
                                                        array('module' => 'vrac','action' => 'visualisation'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object'))); 
                                                        
        $r->prependRoute('vrac_rectificative', new VracRoute('/vrac/:identifiant/:numero_contrat/rectificatif',
                                                        array('module' => 'vrac','action' => 'rectificative'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));    
                                                        
        $r->prependRoute('vrac_modificative', new VracRoute('/vrac/:identifiant/:numero_contrat/modificatif',
                                                        array('module' => 'vrac','action' => 'modificative'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));                                                     
                                                        
        $r->prependRoute('vrac_validation', new VracRoute('/vrac/:identifiant/:numero_contrat/validation/:acteur',
                                                        array('module' => 'vrac','action' => 'validation', 'acteur' => null),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true)));
                                                        
        $r->prependRoute('vrac_statut', new VracRoute('/vrac/:identifiant/:numero_contrat/statut/:statut',
                                                        array('module' => 'vrac','action' => 'statut'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true))); 

        $r->prependRoute('vrac_modification_restreinte', new VracRoute('/vrac/:identifiant/:numero_contrat/modification-restreinte',
                                                        array('module' => 'vrac','action' => 'modificationRestreinte'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true)));
                                                        
        $r->prependRoute('vrac_modification', new VracRoute('/vrac/:identifiant/:numero_contrat/modification',
                                                        array('module' => 'vrac','action' => 'modification'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object', 'no_archive' => true)));

        $r->prependRoute('vrac_pdf', new VracRoute('/vrac/:identifiant/:numero_contrat/pdf.:format', 
                                                          array('module' => 'vrac', 'action' => 'pdf', 'format' => 'pdf'),
                                                          array('sf_method' => array('get'), 'format' => '(html|pdf)'),
                                                          array('model' => 'Vrac', 'type' => 'object')));

        $r->prependRoute('vrac_pdf_transaction', new VracRoute('/vrac/:identifiant/:numero_contrat/pdf-transaction.:format', 
                                                          array('module' => 'vrac', 'action' => 'pdfTransaction', 'format' => 'pdf'),
                                                          array('sf_method' => array('get'), 'format' => '(html|pdf)'),
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

        /*$r->prependRoute('vrac_produit_cvo', new VracRoute('/vrac/:identifiant/:numero_contrat/produit/:hash/cvo',
                                                        array('module' => 'vrac','action' => 'getCvo'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));*/
 
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