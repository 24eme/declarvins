<tr class="<?php if($alt): ?>alt<?php endif; ?>">
    <td>
        <?php if($drm->isMaster()): ?><strong><?php endif; ?>

        <?php if($drm->getRectificative() > 0): ?>
            <?php echo sprintf('%s R%02d', $drm->periode, $drm->getRectificative()) ?>
        <?php else: ?>
            <?php echo sprintf('%s', $drm->periode) ?>
        <?php endif; ?>

        <?php if($drm->isMaster()): ?></strong><?php endif; ?>

        <?php if($drm->getModificative() > 0): ?>
            <?php echo sprintf('(M%02d)', $drm->getModificative()) ?>
        <?php endif; ?>
    </td>
    <td>
    <?php if ($drm->isNew()): ?>
        Nouvelle
    <?php elseif ($drm->isValidee()): ?>
        OK
    <?php else: ?>
	   En cours
    <?php endif; ?>
    </td>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    <td>
        <?php echo $drm->getModeDeSaisieLibelle() ?>
    </td>
    <?php endif; ?>
    <td>
        <?php if($drm->isNew()): ?>
        	<?php if ($drm->isDebutCampagne() && !$drm->hasDaidsCampagnePrecedente()): ?>
        		Vous devez saisir votre <strong>DAI/DS <?php echo $drm->getCampagnePrecedente() ?></strong>
        	<?php else: ?>
            	<a href="<?php echo url_for('drm_nouvelle', $drm) ?>" class="btn_reinitialiser"><span>Démarrer la DRM</span></a>
            <?php endif; ?>
        <?php elseif ($drm->isValidee()): ?>
            <a href="<?php echo url_for('drm_visualisation', $drm) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
        <?php else: ?>
        	<?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
		    <a href="<?php echo url_for('drm_init', $drm); ?>">Accéder à la déclaration en cours</a><br />
		    <?php endif; ?>
        <?php endif; ?>
	</td>
	<?php if (!$drm->isNew() && ($drm->isSupprimable() || ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $drm->isSupprimableOperateur())) && $drm->isMaster()): ?>	
	<td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
		<a href="<?php echo url_for('drm_delete_one', $drm); ?>" class="btn_reinitialiser"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
	</td>
	<?php endif; ?>			
</tr>