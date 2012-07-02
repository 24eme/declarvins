<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<div class="popup_form">
		<div class="ligne_form_btn" style="margin:0; text-align: left;">
			<form action="<?php echo url_for('vrac_recherche') ?>" method="get" style="width: 500px; float:left;">
				<input name="identifiant" value="<?php echo (isset($identifiant)) ? $identifiant : '' ; ?>"/>
				<button type="submit" class="btn_valider">Rechercher</button>
			</form>
			<a href="<?php echo url_for('vrac_nouveau') ?>" class="btn_annuler" style="float:right;">Nouveau</a>
		</div>
		<div style="clear:both;">&nbsp;</div>
	</div>
	<?php include_partial('table_contrats', array('vracs' => $vracs)); ?>
</section>