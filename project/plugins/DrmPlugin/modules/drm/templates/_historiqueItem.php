<tr class="<?php if($alt): ?>alt<?php endif; ?>">
<td>
	<?php echo $titre; ?>
</td>
<?php if (!$valide): ?>
	<td>En cours</td>
	<td>
		<a href="<?php echo url_for('drm_init', array('campagne_rectificative' => $campagne_rectificative)) ?>">Accéder à la déclaration en cours</a><br />
		<a href="#" class="btn_reinitialiser"><span>Réinitialiser la déclaration</span></a>
	</td>
<?php else: ?>
	<td>OK</td>
	<td>
		<a href="<?php echo url_for('drm_rectificative', array('campagne_rectificative' => $campagne_rectificative)) ?>">
		  	Soumettre une DRM rectificative
		</a><br />
			<a href="<?php echo url_for('drm_visualisation', array('campagne_rectificative' => $campagne_rectificative)) ?>" class="btn_reinitialiser"><span>Visualiser</span></a>
		</td>							
	<?php endif; ?>
</tr>