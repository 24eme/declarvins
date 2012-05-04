<?php include_partial('global/navTop', array('active' => 'vrac')); ?>

<section id="contenu">
    <h1>Vrac</h1>
    <p class="intro">Historique de vos contrats Vrac</p>
    <section id="principal">
	    <div id="recap_drm">
			<div id="drm_annee_courante">
				<?php include_partial('vrac/historiqueList', array('config' => $config, 'vracs' => $historique->getVracParAnneeCourante())) ?>
			</div>
		</div>
		
		<ul id="nav_drm_annees">
			<?php foreach ($historique->getAnnees() as $annee): ?>
				<?php if ($annee == $historique->getAnneeCourante()): ?>
					<li class="actif"><strong>Vrac <?php echo $annee ?></strong></li>
				<?php else: ?>
					<li><a href="<?php echo url_for('vrac_historique', array('annee' => $annee))?>">Vrac <?php echo $annee ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</section>
</section>
