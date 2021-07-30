<?php

class DSNegoceUploadRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();

        $r->prependRoute('dsnegoceupload_mon_espace', new EtablissementRoute('/dsnegoceupload/:identifiant', array('module' => 'dsnegoceupload',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));

        $r->prependRoute('dsnegoceupload_upload', new EtablissementRoute('/dsnegoceupload/:identifiant/import', array('module' => 'dsnegoceupload',
                    'action' => 'upload'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));



    }
}
