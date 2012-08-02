<?php include_component('global', 'navBack', array('active' => 'douanes')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Douanes &nbsp;<a href="<?php echo url_for('douane_nouveau') ?>" class="btn_ajouter"></a></h1>
	    <div class="tableau_ajouts_liquidations">
	    <table class="tableau_recap">
            <thead>
    			<?php include_partial('douane/itemHeader') ?>
            </thead>
            <tbody>
	    	<?php foreach($douanes->rows as $douane): ?>
	    		<?php include_partial('douane/item', array('douane' => $douane)) ?>
	    	<?php endforeach; ?>
	    	</tbody>
    	</table>
	    </div>
	</div>
	</section>
</section>