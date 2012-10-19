<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <h1>Historique de vos Déclarations Récapitulatives Mensuelles</h1>
    <section id="principal">
	    <div id="recap_drm">
			<div id="drm_annee_courante">
				<?php include_component('drm', 'historiqueList', array('campagne' => $campagne, 'etablissement' => $etablissement)) ?>
			</div>
		</div>

		<?php include_component('drm', 'campagnes', array('campagne' => $campagne, 'etablissement' => $etablissement)); ?>

	</section>
</section>
