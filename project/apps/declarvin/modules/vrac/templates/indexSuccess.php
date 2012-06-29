<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<div style="margin: 10px;">
		<form action="" method="get">
			<input name="identifiant" value="<?php echo (isset($identifiant)) ? $identifiant : '' ; ?>"/> <input type="submit" value="recherche"/>
		</form>
	</div>
	<?php include_partial('table_contrats', array('vracs' => $vracs)); ?>
</section>