<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'produits')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Produits &nbsp;<a href="<?php echo url_for('configuration_produit_nouveau') ?>" class="btn_ajouter">Ajouter</a><a href="<?php echo url_for('configuration_produit_import') ?>" class="btn_ajouter">Importer</a><a href="<?php echo url_for('configuration_produit_export') ?>" class="btn_ajouter">Exporter</a></h1>
	    
	    <?php include_component('configuration_produit', 'catalogueProduit', array('configurationProduits' => $configurationProduits)) ?>
	    
	    <?php include_component('configuration_produit', 'prestationsProduit', array('configurationProduits' => $configurationProduits)) ?>
	</div>
	</section>
</section>
