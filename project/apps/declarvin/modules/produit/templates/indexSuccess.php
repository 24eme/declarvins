<?php include_component('global', 'navBack', array('active' => 'produits')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Produits &nbsp;<a href="<?php echo url_for('produit_nouveau') ?>" class="btn_ajouter"></a></h1>
	    <div class="tableau_ajouts_liquidations">
	    <table class="tableau_recap">
            <thead>
    			<?php include_partial('produit/itemHeader') ?>
            </thead>
            <tbody>
	    	<?php $i = 0; foreach($produits->rows as $produit): ?>
	    		<?php include_partial('produit/item', array('i' => $i, 'produit' => $produit, 'supprimable' => (!in_array($produit->key[7], $produitsNonSupprimables->getRawValue())))) ?>
	    	<?php $i++; endforeach; ?>
	    	</tbody>
    	</table>
	    </div>
	</div>
	</section>
</section>
