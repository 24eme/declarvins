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
			      <td><?php echo $futurDrm[DRMHistorique::VIEW_INDEX_ANNEE].'-'.$futurDrm[DRMHistorique::VIEW_INDEX_MOIS] ?></td>
			<td>NOUVELLE</td>
			<td>
				<a href="<?php echo url_for('drm_nouvelle') ?>">Démarrer la DRM</a><br />
			</td>
		</tr>
		<?php endif; ?>
		<?php foreach ($list as $drm_id => $drm): ?>
		<?php include_component('drm', 'historiqueItem', array('alt' => $i%2 == 0, 'drm' => $drm)) ?>
		<?php $i++; if (isset($limit) && $limit == $i) break; endforeach; ?>
	</tbody>
</table>
