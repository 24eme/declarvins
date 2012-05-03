<section id="contenu">
	<?php include_partial('global/navTop', array('active' => 'drm')) ?>
	<div id="creation_compte" style="width:70%; float: left;">
		<h1>Déclaration Récapitulative Mensuelle</h1>
		<p>Bienvenue sur votre espace DRM. Que voulez-vous faire ?</p>
		<br /><br />
		<ul>
			<li><a href="#">Accédez à mon historique de DRM &raquo;</a></li>
			<li><a href="<?php echo url_for('@drm_init') ?>">Saisir ma DRM en cours &raquo;</a></li>
			<li><a href="#">Soumettre une DRM récapitulative &raquo;</a></li>
		</ul>
	</div>
	<?php include_partial('global/aides') ?>
	<div style="clear:both;">&nbsp;</div>
</section>