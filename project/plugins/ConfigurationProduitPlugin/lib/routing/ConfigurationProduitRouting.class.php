<?php

class ConfigurationProduitRouting
{

    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) 
    {
		$r = $event->getSubject();   
		$r->prependRoute('configuration_produit', new sfRoute('/admin/produits', array('module' => 'configuration_produit', 'action' => 'index'))); 
		$r->prependRoute('configuration_produit_import', new sfRoute('/admin/produits/import', array('module' => 'configuration_produit', 'action' => 'import')));  
		$r->prependRoute('configuration_produit_export', new sfRoute('/admin/produits/export', array('module' => 'configuration_produit', 'action' => 'export')));  
		$r->prependRoute('configuration_produit_nouveau', new sfRoute('/admin/produits/nouveau', array('module' => 'configuration_produit', 'action' => 'nouveau')));
		$r->prependRoute('configuration_produit_modification', new sfRoute('/admin/produits/modification', array('module' => 'configuration_produit', 'action' => 'modification'))); 
		$r->prependRoute('configuration_produit_suppression', new sfRoute('/admin/produits/suppression', array('module' => 'configuration_produit', 'action' => 'suppression')));  
    }

}
