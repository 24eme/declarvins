<?php 
	$drm = $drm->getRawValue(); 
	$drm = DRMClient::getInstance()->find('DRM-'.$drm[0].'-'.$drm[1].'-'.$drm[2]);

?>
<tr class="<?php if($alt): ?>alt<?php endif; ?>">
<td>
	<?php if($derniere): ?>
		<strong><?php echo $titre; ?></strong>
	<?php else: ?>
		<?php echo $titre; ?>
	<?php endif; ?>
</td>
<?php if (!$valide): ?>
	<td>En cours</td>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
        <td><?php echo $drm->mode_de_saisie ?></td>
    <?php endif; ?>
    <td>
		<a href="<?php echo url_for('drm_init', array('campagne_rectificative' => $campagne_rectificative)); ?>">Accéder à la déclaration en cours</a><br />
	   </td>
	   <td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
	       <a href="<?php echo url_for('drm_delete', array('campagne_rectificative' => $campagne_rectificative)); ?>" class="btn_reinitialiser"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
	   </td>
<?php else: ?>
	<td>OK</td>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
        <td><?php echo $drm->mode_de_saisie ?></td>
    <?php endif; ?>
	<td>
			<a href="<?php echo url_for('drm_visualisation', array('campagne_rectificative' => $campagne_rectificative)) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
		</td>	
		<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN) && !$drm->isEnvoyee()): ?>	
	<td style="border: 0px; padding-left: 0px;background-color: #ffffff;">
		<a href="<?php echo url_for('drm_delete', array('campagne_rectificative' => $campagne_rectificative)); ?>" class="btn_reinitialiser"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
	</td>
	<?php endif; ?>					
	<?php endif; ?>
</tr>