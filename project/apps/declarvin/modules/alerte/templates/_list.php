<div class="tableau_ajouts_liquidations">
	<table class="tableau_recap">
		<thead>
			<tr>
				<th><strong>Statut</strong></th>
				<th><strong>Etablissement</strong></th>
				<th><strong>Alerte</strong></th>
			</tr>
		</thead>
		<tbody>
		<?php $i=0; foreach ($alertes->rows as $alerte): $derniere_alerte = $alerte->value->derniere_alerte;?>
			<tr <?php if($i%2): ?> class="alt" <?php endif; ?>>
				<td>Statut <?php echo $derniere_alerte->statut ?></td>
				<td><?php echo $alerte->value->etablissement_identifiant; ?></td>
				<td><?php echo $alerte->value->date_derniere_alerte ?><br /><?php echo $derniere_alerte->detail ?></td>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
</div>
