<?php

class acVinEtablissementRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('etablissement_autocomplete_all', new sfRoute('/etablissement/autocomplete/tous', 
        															   array('module' => 'etablissement_autocomplete', 
                                                                             'action' => 'all')));
		
		$r->prependRoute('etablissement_autocomplete_byfamilles', new sfRoute('/etablissement/autocomplete/familles/:familles', 
        															   array('module' => 'etablissement_autocomplete', 
                                                                             'action' => 'byFamilles')));

    }

}