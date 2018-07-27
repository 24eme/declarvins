<?php

class DSNegoceRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();
        
        $r->prependRoute('dsnegoce_mon_espace', new EtablissementRoute('/dsnegoce/:identifiant', array('module' => 'dsnegoce',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
        
        $r->prependRoute('dsnegoce_upload', new EtablissementRoute('/dsnegoce/:identifiant/import', array('module' => 'dsnegoce',
                    'action' => 'upload'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));

        

    }
}
