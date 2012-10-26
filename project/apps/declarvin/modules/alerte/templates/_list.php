<?php use_helper('Date'); ?> 
<div class="tableau_ajouts_liquidations">
	<table class="tableau_recap">
		<thead>
			<tr>
				<th><strong>Etablissement</strong></th>
				<th><strong>Date</strong></th>
				<th><strong>Alerte</strong></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$i=0; 
			foreach ($alertes->rows as $alerte): 
			$key = $alerte->key;
			$derniere_alerte = $alerte->value->derniere_alerte;
		?>
			<tr <?php if($i%2): ?> class="alt" <?php endif; ?>>
				<td><?php echo $alerte->value->etablissement_identifiant; ?></td>
				<td><?php echo format_date($alerte->value->date_derniere_alerte, 'f', 'fr_FR'); ?></td>
				<td><strong><?php echo $derniere_alerte->detail ?></strong><br /><i><?php echo $derniere_alerte->commentaire ?></i></td>
				<td class="actions"><a class="btn_modifier" href="<?php echo url_for('alerte_edit', array('id' => $key[AlertesAllView::KEY_ID])) ?>">Edit</a></td>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
</div>
