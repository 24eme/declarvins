<?php include_partial('global/navBack', array('active' => 'produits')); ?>
<section id="contenu">
	<section id="principal">
	<div class="clearfix" id="application_dr">
	    <h1>Produits <a href="<?php echo url_for('produit_nouveau') ?>">+</a></h1>
	    <div class="tableau_ajouts_liquidations">
	    <table class="tableau_recap">
            <thead>
    			<?php include_partial('produit/itemHeader') ?>
            </thead>
            <tbody>
	    	<?php foreach($produits->rows as $produit): ?>
	    		<?php include_partial('produit/item', array('produit' => $produit, 'supprimable' => (!in_array($produit->key[7], $produitsNonSupprimables->getRawValue())))) ?>
	    	<?php endforeach; ?>
	    	</tbody>
    	</table>
	    </div>
	</div>
	</section>
</section>