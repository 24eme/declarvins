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
		
		<ul id="nav_drm_annees">
			<?php foreach ($historique->getAnnees() as $annee): ?>
				<?php if ($annee == $historique->getAnneeCourante()): ?>
					<li class="actif"><strong>DRM <?php echo $annee ?></strong></li>
				<?php else: ?>
					<li><a href="<?php echo url_for('drm_historique', array('annee' => $annee))?>">DRM <?php echo $annee ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</section>
</section>
