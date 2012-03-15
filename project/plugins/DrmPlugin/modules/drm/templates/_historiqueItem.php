<tr class="<?php if($alt): ?>alt<?php endif; ?>">
<td>
	<?php echo $drm[DRMHistorique::VIEW_INDEX_ANNEE].'-'.$drm[DRMHistorique::VIEW_INDEX_MOIS] ?>
	<?php if($drm[DRMHistorique::VIEW_INDEX_RECTIFICATIVE]): ?>
		R<?php echo $drm[DRMHistorique::VIEW_INDEX_RECTIFICATIVE] ?>
	<?php endif; ?>
</td>
<?php if (!$drm[DRMHistorique::VIEW_INDEX_STATUS]): ?>
	<td>En cours</td>
	<td>
		<a href="<?php echo url_for('drm_init') ?>">Accéder à la déclaration en cours</a><br />
		<a href="#" class="btn_reinitialiser"><span>Réinitialiser la déclaration</span></a>
	</td>
<?php else: ?>
	<td>OK</td>
	<td>
		<a href="<?php echo url_for(array('sf_route' => 'drm_rectificative', 
										  'campagne' => $drm[DRMHistorique::VIEW_INDEX_ANNEE].'-'.$drm[DRMHistorique::VIEW_INDEX_MOIS],
										  'rectificative' => $drm[DRMHistorique::VIEW_INDEX_RECTIFICATIVE])) ?>">
		  	Soumettre une DRM rectificative
		</a><br />
			<a href="#" class="btn_reinitialiser"><span>Visualiser</span></a>
		</td>							
	<?php endif; ?>
</tr>