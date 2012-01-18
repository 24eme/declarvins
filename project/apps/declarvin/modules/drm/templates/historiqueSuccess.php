<?php include_partial('global/navTop'); ?>

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
							<th>Etat validation</th>
							<th>Etat réception</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($drms as $drm): ?>
						<tr<?php if($i%2==0): ?> class="alt"<?php endif; ?>>
							<td><?php echo $drm->campagne ?></td>
							<td><?php if ($anneeFixe == $annee): ?>En cours<?php else: ?>OK<?php endif; ?></td>
							<td><?php if ($anneeFixe == $annee): ?>&nbsp;<?php else: ?>OK<?php endif; ?></td>
							<td>
								<?php if ($anneeFixe == $annee): ?><a href="<?php echo url_for('@drm_init') ?>">Accéder à la déclaration en cours</a><?php else: ?><a href="#">Soumettre une DRM rectificative</a><?php endif; ?><br />
								<a href="#" class="btn_reinitialiser"><span><?php if ($anneeFixe == $annee): ?>Réinitialiser la déclaration<?php else: ?>Visualiser<?php endif; ?></span></a>
							</td>
						</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>
			
			<div id="drm_autres_annees">
				<?php foreach ($annees as $an): ?>
				<?php if ($an != $annee): ?>
					<a href="<?php echo url_for('drm_historique', array('annee' => $an))?>">Afficher les drm <?php echo $an ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
			</div>
		</div>
		
		<ul id="nav_drm_annees">
			<?php foreach ($annees as $an): ?>
				<?php if ($an == $annee): ?>
					<li class="actif"><strong>DRM <?php echo $an ?></strong></li>
				<?php else: ?>
					<li><a href="<?php echo url_for('drm_historique', array('annee' => $an))?>">DRM <?php echo $an ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</section>
</section>
