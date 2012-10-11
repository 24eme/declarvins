<div class="tableau_ajouts_liquidations">
	<table class="tableau_recap">
		<thead>
			<tr>
				<th><strong>Code</strong></th>
				<th><strong>Libellé</strong></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php $i=0; foreach ($object as $key => $libelle): ?>
			<tr <?php if($i%2): ?> class="alt" <?php endif; ?>>
				<td><?php echo $key; ?></td>
				<td><?php echo $libelle; ?></td>
				<td class="actions"><a class="btn_modifier"
					href="<?php echo url_for('admin_libelles_edit', array('type' => $type, 'key' => $key)) ?>">Edit</a>
				</td>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
</div>
