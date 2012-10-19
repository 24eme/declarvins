<tr class="<?php if($alt): ?>alt<?php endif; ?>">
    <td>
        <?php echo $drm->periode ?>
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
            <a href="<?php echo url_for('drm_nouvelle', $drm) ?>" class="btn_reinitialiser"><span>Démarrer la DRM</span></a>
        <?php elseif ($drm->isValidee()): ?>
            <a href="<?php echo url_for('drm_visualisation', $drm) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
        <?php else: ?>
		    <a href="<?php echo url_for('drm_init', $drm); ?>">Accéder à la déclaration en cours</a><br />
        <?php endif; ?>
	</td>
	<?php if (!$drm->isNew() && ($drm->isSupprimable() || ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $drm->isSupprimableOperateur()))): ?>	
	<td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
		<a href="<?php echo url_for('drm_delete', $drm); ?>" class="btn_reinitialiser"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
	</td>
	<?php endif; ?>					
</tr>