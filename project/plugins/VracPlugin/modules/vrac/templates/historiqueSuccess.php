<?php include_partial('global/navTop', array('active' => 'vrac')); ?>

<section id="contenu">
    <h1>Historique de vos contrats Vrac</h1>
    <section id="principal">
	    <div id="recap_drm">
			<div id="drm_annee_courante">
				<?php include_partial('vrac/historiqueList', array('config' => $config, 'vracs' => $historique->getVracParCampagneCourante())) ?>
			</div>
		</div>
		
		<ul id="nav_drm_annees">
			<?php foreach ($historique->getCampagnes() as $campagne): ?>
				<?php if ($campagne == $historique->getCampagneCourante()): ?>
					<li class="actif"><strong>Vrac <?php echo $campagne ?></strong></li>
				<?php else: ?>
					<li><a href="<?php echo url_for('vrac_historique', array('campagne' => $campagne))?>">Vrac <?php echo $campagne ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</section>
</section>
