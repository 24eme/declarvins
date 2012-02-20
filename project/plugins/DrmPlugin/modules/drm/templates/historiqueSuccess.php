<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <h1>Déclaration Récapitulative Mensuelle</h1>
    <p class="intro">Bienvenue sur votre espace DRM. Que pensez-vous faire ?</p>
    <section id="principal">
	    <div id="recap_drm">
			<div id="drm_annee_courante">
				<table>
					<thead>
						<tr>
							<th>DRM</th>
							<th>Etat</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($historique->getDrmsParAnneeCourante() as $drm_id => $drm): ?>
						<tr<?php if($i%2==0): ?> class="alt"<?php endif; ?>>
							<td><?php echo $drm[1].'-'.$drm[2] ?></td>
							<?php if (!$drm[3]): ?>
							<td>En cours</td>
							<td>
								<a href="<?php echo url_for('drm_init') ?>">Accéder à la déclaration en cours</a><br />
								<a href="#" class="btn_reinitialiser"><span>Réinitialiser la déclaration</span></a>
							</td>
							<?php else: ?>
							<td>OK</td>
							<td>
								<a href="#">Soumettre une DRM rectificative</a><br />
								<a href="#" class="btn_reinitialiser"><span>Visualiser</span></a>
							</td>							
							<?php endif; ?>
						</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
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
