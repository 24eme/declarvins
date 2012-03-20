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
	<td>
		<a href="<?php echo url_for('drm_init', array('campagne_rectificative' => $campagne_rectificative)); ?>">Accéder à la déclaration en cours</a><br />
	   </td><td style="border: 0px; padding-left: 0px;">
		<a href="<?php echo url_for('drm_delete', array('campagne_rectificative' => $campagne_rectificative)); ?>" class="btn_reinitialiser"><span><img src="/images/pictos/pi_supprimer.png"/></span></a>
	</td>
<?php else: ?>
	<td>OK</td>
	<td>
		<?php if($derniere): ?>
		<a href="<?php echo url_for('drm_rectificative', array('campagne_rectificative' => $campagne_rectificative)) ?>">
		  	Soumettre une DRM rectificative
		</a>
		<br />
		<?php endif; ?>
			<a href="<?php echo url_for('drm_visualisation', array('campagne_rectificative' => $campagne_rectificative)) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
		</td>							
	<?php endif; ?>
</tr>