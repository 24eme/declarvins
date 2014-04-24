<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'douanes')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Etablissements appartenants au service douane <strong><?php echo $douane->nom ?></strong></h1>
	    <div class="tableau_ajouts_liquidations">
	    <?php if (count($etablissements) > 0): ?>
	    <table class="tableau_recap">
            <tbody>
	    	<?php foreach($etablissements as $etablissement): ?>
	    		<tr><td><?php echo EtablissementAllView::makeLibelle($etablissement) ?></td></tr>
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