<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'douanes')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Douanes &nbsp;<a href="<?php echo url_for('douane_nouveau') ?>" class="btn_ajouter">Ajouter</a></h1>
	    <div class="tableau_ajouts_liquidations">
		    <table class="tableau_recap">
	            <thead>
	    			<?php include_partial('douane/itemHeader') ?>
	            </thead>
	            <tbody>
	            <?php $alt = true; ?>
		    	<?php foreach($douanes->rows as $douane): ?>
		    		<?php $alt = !$alt; ?>
		    		<?php include_partial('douane/item', array('douane' => $douane, 'alt' => $alt)) ?>
		    	<?php endforeach; ?>
		    	</tbody>
	    	</table>
	    </div>
	</div>
	</section>
</section>