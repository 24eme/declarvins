<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <h1>Déclaration Récapitulative Mensuelle</h1>
    <p class="intro">Bienvenue sur votre espace DRM. Que pensez-vous faire ?</p>
    
    <section id="principal">
	    <div id="recap_drm">
			<div id="drm_annee_courante">
				<?php include_component('drm', 'historiqueList', array('historique' => $historique)) ?>
			</div>
		</div>
	</section>
	<a href="<?php echo url_for('@drm_historique') ?>">Votre historique complet &raquo;</a>

</section>
