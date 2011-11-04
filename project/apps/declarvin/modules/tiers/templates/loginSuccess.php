<section id="contenu">
	<div id="creation_compte" style="width:70%; float: left;">
		<h1>Sélection des établissements</h1>
		<p>Accédez ici au formulaire de DRM, à l'historique de vos DRM ou demandez à procéder à une DRM rectificative</p>
		<span>Attention, vous n'avez plus que 4 jours pour soumettre votre DRM du mois en cours</span>
		<br /><br />
		<ul>
			<?php foreach ($compte->tiers as $tiers): ?>
			<li><a href="<?php echo url_for('tiers', array('tiers_id' => $tiers->id)) ?>"><?php echo $tiers->nom ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php include_partial('global/aides') ?>
	<div style="clear:both;">&nbsp;</div>
</section>