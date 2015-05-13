<?php include_component('global', 'navTop', array('active' => 'drm')); ?>
<section id="contenu">
	<h1>Un problème est survenu</h1>
	<p>Vous n'avez pas encore validé votre DRM.</p>
	<p>Vous ne pouvez donc pas accéder à la page demandée.</p>
	<div id="btn_etape_dr">
    	<a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>" class="btn_suiv">
			<span>Retour à mon espace</span>
		</a>
	</div>
</section>