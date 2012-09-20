<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <h1>Historique de vos Déclarations Récapitulatives Mensuelles</h1>
    <section id="principal">
	    <div id="recap_drm">
			<div id="drm_annee_courante">
				<?php include_component('drm', 'historiqueList', array('new_drm' => false, 'historique' => $historique, 'etablissement' => $etablissement)) ?>
			</div>
		</div>
		
		<ul id="nav_drm_annees">
			<?php $first = true; foreach ($historique->getCampagnes() as $campagne): ?>
				<?php if ($first): ?>
				<li><a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>">DRM <?php echo $campagne ?></a></li>
				<?php else:?>
					<?php if ($campagne == $historique->getCampagneCourante()): ?>
						<li class="actif"><strong>DRM <?php echo $campagne ?></strong></li>
					<?php else: ?>
						<li><a href="<?php echo url_for('drm_historique', array('campagne' => $campagne, 'identifiant' => $etablissement->identifiant))?>">DRM <?php echo $campagne ?></a></li>
					<?php endif; ?>
				<?php endif;?>
			<?php $first = false; endforeach; ?>
		</ul>
	</section>
</section>
