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
						<?php $i=0; if ($hasNewDrm): $i++; ?>
						<tr class="alt">
							      <td><?php echo $futurDrm[DRMHistorique::$VIEW_INDEX_ANNEE].'-'.$futurDrm[DRMHistorique::$VIEW_INDEX_MOIS] ?></td>
							<td>NOUVELLE</td>
							<td>
								<a href="<?php echo url_for('drm_init') ?>">Démarrer la DRM</a><br />
							</td>
						</tr>
						<?php endif; ?>
						<?php foreach ($historique->getSliceDrms($nbDrmHistory) as $drm_id => $drm): ?>
						<tr<?php if($i%2==0): ?> class="alt"<?php endif; ?>>
							<td><?php echo $drm[DRMHistorique::$VIEW_INDEX_ANNEE].'-'.$drm[DRMHistorique::$VIEW_INDEX_MOIS] ?></td>
							<?php if (!$drm[DRMHistorique::$VIEW_INDEX_STATUS]): ?>
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
	</section>
	<a href="<?php echo url_for('@drm_historique') ?>">Votre historique complet &raquo;</a>
    
    
    
    <!-- 
    <div id="creation_compte" style="width:70%; float: left;">

        <br /><br />
        <ul>
        <?php if (!$sf_user->getDrm() || $sf_user->getDrm()->isNew()): ?>
                    <li><a href="<?php echo url_for('@drm_init') ?>">Commencer ma DRM &raquo;</a></li>
        <?php else: ?>
                    <li><a href="<?php echo url_for('@drm_init') ?>">Continuer ma DRM en cours &raquo;</a></li>
        <?php endif; ?>
                <li><a href="<?php echo url_for('@drm_historique') ?>">Votre historique &raquo;</a></li>
        </ul>

    </div>
    <?php include_partial('global/aides') ?>
    <div style="clear:both;">&nbsp;</div>
     -->
</section>
