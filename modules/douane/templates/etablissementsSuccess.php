<?php include_component('global', 'navBack', array('active' => 'douanes')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Etablissements appartenants au service douane <strong><?php echo $douane->nom ?></strong></h1>
	    <div class="tableau_ajouts_liquidations">
	    <?php if (count($etablissements->rows) > 0): ?>
	    <table class="tableau_recap">
            <tbody>
	    	<?php foreach($etablissements->rows as $etablissement): ?>
	    		<tr><td><?php echo EtablissementDouaneView::makeLibelle($etablissement->value) ?></td></tr>
	    	<?php endforeach; ?>
	    	</tbody>
    	</table>
    	<?php else: ?>
    	Aucun établissement
    	<?php endif; ?>
	    </div>
	</div>
	</section>
</section>