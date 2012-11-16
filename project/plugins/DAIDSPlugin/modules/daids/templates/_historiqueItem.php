<tr class="<?php if($alt): ?>alt<?php endif; ?>">
    <td>
        <?php if($daids->isMaster()): ?><strong><?php endif; ?>

        <?php if($daids->getRectificative() > 0): ?>
            <?php echo sprintf('%s R%02d', $daids->periode, $daids->getRectificative()) ?>
        <?php else: ?>
            <?php echo sprintf('%s', $daids->periode) ?>
        <?php endif; ?>

        <?php if($daids->isMaster()): ?></strong><?php endif; ?>

        <?php if($daids->getModificative() > 0): ?>
            <?php echo sprintf('(M%02d)', $daids->getModificative()) ?>
        <?php endif; ?>
    </td>
    <td>
    <?php if ($daids->isNew()): ?>
        Nouvelle
    <?php elseif ($daids->isValidee()): ?>
        OK
    <?php else: ?>
	   En cours
    <?php endif; ?>
    </td>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    <td>
        <?php echo $daids->getModeDeSaisieLibelle() ?>
    </td>
    <?php endif; ?>
    <td>
        <?php if($daids->isNew()): ?>
            <a href="<?php echo url_for('daids_nouvelle', $daids) ?>" class="btn_reinitialiser"><span>Démarrer la DAI/DS</span></a>
        <?php elseif ($daids->isValidee()): ?>
            <a href="<?php echo url_for('daids_visualisation', $daids) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
        <?php else: ?>
		    <a href="<?php echo url_for('daids_init', $daids); ?>">Accéder à la déclaration en cours</a><br />
        <?php endif; ?>
	</td>
	<?php if (!$daids->isNew() && ($daids->isSupprimable() || ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $daids->isSupprimableOperateur()))): ?>	
	<td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
		<a href="<?php echo url_for('daids_delete', $daids); ?>" class="btn_reinitialiser"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
	</td>
	<?php endif; ?>					
</tr>