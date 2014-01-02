<?php

class ConfigurationProduitRouting
{

    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) 
    {
		$r = $event->getSubject();   
		$r->prependRoute('configuration_produit', new sfRoute('/admin/new/produits', array('module' => 'configuration_produit', 'action' => 'index'))); 
		$r->prependRoute('configuration_produit_import', new sfRoute('/admin/new/produits/import', array('module' => 'configuration_produit', 'action' => 'import')));  
		$r->prependRoute('configuration_produit_export', new sfRoute('/admin/new/produits/export', array('module' => 'configuration_produit', 'action' => 'export')));  
		$r->prependRoute('configuration_produit_nouveau', new sfRoute('/admin/new/produits/nouveau', array('module' => 'configuration_produit', 'action' => 'nouveau')));
		$r->prependRoute('configuration_produit_modification', new sfRoute('/admin/new/produits/modification', array('module' => 'configuration_produit', 'action' => 'modification')));  
    }

}
