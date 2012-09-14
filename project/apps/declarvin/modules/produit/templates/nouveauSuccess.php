<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'produits')); ?>
<section id="contenu">
	<section id="principal">
	<div class="clearfix" id="application_dr">
	    <h1>Produits</h1>
	    <?php include_partial('produit/formNouveau', array('form' => $form)) ?>
	</div>
	</section>
</section>