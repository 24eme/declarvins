<table>
	<thead>
		<tr>
			<th>Numéro</th>
			<th>Date</th>
			<th>Produit</th>
			<th>Vol. promis</th>
			<th>Acheteur</th>
			<th>Courtier</th>
			<th>Etat</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$config = $config->getRawValue();
			foreach ($vracs as $vrac):
		?>
		<tr>
			<td><?php echo $vrac[VracHistorique::VIEW_INDEX_NUMERO] ?></td>
			<td><?php echo $vrac[VracHistorique::VIEW_INDEX_DATE_CREATION] ?></td>
			<?php if ($config->get($vrac[VracHistorique::VIEW_INDEX_PRODUIT]) instanceof _ConfigurationDeclaration): ?>
			<td><?php echo implode(' ', array_filter($config->get($vrac[VracHistorique::VIEW_INDEX_PRODUIT])->getLibelles())); ?></td>
			<?php else: ?>
			<td><?php echo implode(' ', array_filter($config->get($vrac[VracHistorique::VIEW_INDEX_PRODUIT])->getParent()->getLibelles())); ?></td>
			<?php endif;?>
			<td><?php echo $vrac[VracHistorique::VIEW_INDEX_VOLUME] ?></td>
			<td><?php echo $vrac[VracHistorique::VIEW_INDEX_ACHETEUR] ?></td>
			<td><?php echo $vrac[VracHistorique::VIEW_INDEX_COURTIER] ?></td>
			<td><?php echo ($vrac[VracHistorique::VIEW_INDEX_ACTIF])? 'Ouvert' : 'Cloturé'; ?></td>
			<td><a href="<?php echo url_for('vrac_switch', array('id' => $vrac[VracHistorique::VIEW_INDEX_ID], 'annee' => $vrac[VracHistorique::VIEW_INDEX_ANNEE]))?>"><?php echo (!$vrac[VracHistorique::VIEW_INDEX_ACTIF])? 'Ouvrir' : 'Cloturer'; ?></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
