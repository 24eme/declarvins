<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'daids')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	<div class="clearfix" id="application_dr">
	    <h1>Taux CGI</h1>
	    <div class="tableau_ajouts_liquidations tableau_recap">
		    <p class="actions">Taux : <?php echo $configuration_daids->reserve_bloque->taux ?>%&nbsp;<a class="btn_modifier" href="<?php echo url_for('admin_daids_edit') ?>">&nbsp;</a></p>
	    </div>
	</div>
	</section>
</section>