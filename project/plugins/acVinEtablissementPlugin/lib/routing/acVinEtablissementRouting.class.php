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

        $r->prependRoute('etablissement_autocomplete_all', new sfRoute('/etablissement/autocomplete/:interpro_id/tous/:only_actif',
                        array('module' => 'etablissement_autocomplete',
                            'action' => 'all')));
                        
        $r->prependRoute('etablissement_autocomplete_all_admin', new sfRoute('/etablissement/admin/autocomplete/:interpro_id/tous/:only_actif',
                        array('module' => 'etablissement_autocomplete',
                            'action' => 'allAdmin')));
        															   
		
		$r->prependRoute('etablissement_autocomplete_byfamilles', new sfRoute('/etablissement/autocomplete/:interpro_id/familles/:familles/:only_actif', 
        															   array('module' => 'etablissement_autocomplete', 
                                                                             'action' => 'byFamilles')));
        															   
		
		$r->prependRoute('etablissement_autocomplete_bysousfamilles', new sfRoute('/etablissement/autocomplete/:interpro_id/familles/:familles/sous-familles/:sous_familles/:only_actif', 
        															   array('module' => 'etablissement_autocomplete', 
                                                                             'action' => 'bySousFamilles')));

    }

}