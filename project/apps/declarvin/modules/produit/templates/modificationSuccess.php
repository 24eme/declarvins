<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'produits')); ?>
<section id="contenu">
	<section id="principal">
	<div class="clearfix" id="application_dr">
	    <h1>Modification du noeud <strong><?php echo ConfigurationProduit::getLibelleNoeud($noeud) ?></strong>.</h1>
	    <?php include_partial('produit/popup', array('form' => $form)) ?>
	</div>
	</section>
</section>