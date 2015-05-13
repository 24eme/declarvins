<tr class="<?php if ($alt): ?>alt<?php endif; ?>">
    <td style="padding: 5px;">
        <?php if ($drm->isMaster()): ?><strong><?php endif; ?>
            <!--
    if($drm->getRectificative() > 0):
    echo sprintf('%s R%02d', $drm->periode, $drm->getRectificative())
    else:
    echo sprintf('%s', $drm->periode)
    endif;
            -->
            <?php echo sprintf('%s %s', $drm->periode, $drm->version); ?>

            <?php if ($drm->isMaster()): ?></strong><?php endif; ?>
        <!-- 
if($drm->getModificative() > 0):
echo sprintf('(M%02d)', $drm->getModificative())
endif;
        -->
    </td>
    <td style="padding: 5px;">
    <?php echo $drm->getLibelleBilan(); ?>
    </td>
        <td style="padding: 5px;">
            <?php echo $drm->getModeDeSaisieLibelle() ?>
        </td>
    <td style="padding: 5px;">
        <?php if ($drm->isNew()): ?>
            <?php if (1 == 2 && $drm->isDebutCampagne() && !$drm->hasDaidsCampagnePrecedente()): ?>
                Vous devez saisir votre <strong>DRM <?php echo $drm->getCampagnePrecedente() ?></strong>
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
    <?php if (!$drm->isNew() && !$drm->isValidee()): ?>	
        <td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
            <a href="<?php echo url_for('drm_delete_one', $drm); ?>" class="btn_reinitialiser" onclick="return confirm('Etes vous surs de vouloir supprimer cette DRM ?')"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
        </td>
    <?php endif; ?>			
</tr>