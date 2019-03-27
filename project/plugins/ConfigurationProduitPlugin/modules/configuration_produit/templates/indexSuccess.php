<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'produits')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Produits <strong><u><?php echo str_replace("INTERPRO-", "", $configurationProduits->interpro) ?></u></strong>&nbsp;<a href="<?php echo url_for('configuration_produit_nouveau', array('anivin' => $sf_request->getParameter('anivin', 0))) ?>" class="btn_ajouter">Ajouter</a><a href="<?php echo url_for('configuration_produit_import', array('anivin' => $sf_request->getParameter('anivin', 0))) ?>" class="btn_ajouter">Importer</a><a href="<?php echo url_for('configuration_produit_export', array('anivin' => $sf_request->getParameter('anivin', 0))) ?>" class="btn_ajouter">Exporter</a></h1>
	    <p class="popup_form" style="text-align: right;">
	    	<span class="ligne_form_btn">
	    	<?php if ($sf_user->getCompte()->getGerantInterpro()->_id != "INTERPRO-ANIVIN"): ?>
	    	<?php if ($configurationProduits->interpro != "INTERPRO-ANIVIN"): ?>
	    	<a class="btn_annuler btn_fermer" href="<?php echo url_for('configuration_produit', array('anivin' => 1)) ?>">Catalogue Produit ANIVINS</a>
	    	<?php else: ?>
	    	<a class="btn_annuler btn_fermer" href="<?php echo url_for('configuration_produit', array('anivin' => 0)) ?>">Catalogue Produit <?php echo $sf_user->getCompte()->getGerantInterpro()->nom ?></a>
	    	<?php endif;?>
	    	<?php endif; ?>
	    	</span>
	    </p>
	    <?php include_component('configuration_produit', 'catalogueProduit', array('configurationProduits' => $configurationProduits)) ?>
	    
	    <?php include_component('configuration_produit', 'prestationsProduit', array('configurationProduits' => $configurationProduits)) ?>
	</div>
	</section>
</section>
