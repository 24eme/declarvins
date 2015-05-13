<?php
class DouaneRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) 
    {
        $r = $event->getSubject();
        
		$r->prependRoute('admin_douanes', new sfRoute('/admin/douanes', array('module' => 'douane', 
									'action' => 'index')));
		$r->prependRoute('douane_nouveau', new sfRoute('/admin/douane/nouveau', array('module' => 'douane', 
									'action' => 'nouveau')));
		$r->prependRoute('douane_modification', new sfRoute('/admin/douane/:id/modification', array('module' => 'douane', 
									'action' => 'modification', 'id' => null)));
		$r->prependRoute('douane_etablissements', new sfRoute('/admin/douane/:id/etablissements', array('module' => 'douane', 
									'action' => 'etablissements', 'id' => null)));
		$r->prependRoute('douane_activer', new sfRoute('/admin/douane/:id/activer', array('module' => 'douane', 
									'action' => 'activer', 'id' => null)));
		$r->prependRoute('douane_desactiver', new sfRoute('/admin/douane/:id/desactiver', array('module' => 'douane', 
									'action' => 'desactiver', 'id' => null)));
    }
}