<div class="tableau_resultats">
	<table>
		<thead>
			<tr>
				<th>Identifiant</th>
				<th>Période</th>
				<th>Saisie</th>
				<th>Raison sociale</th>
				<th>Nom</th>
				<th>Num. IVSE</th>
				<th>Total début de mois</th>
				<th>Total entrées nettes</th>
				<th>Total entrées avec réciproque</th>
				<th>Total sorties nettes</th>
				<th>Total sorties avec réciproque</th>
				<th>Total fin de mois</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		foreach ($hits as $hit): 
		$item = $hit->getData();
		$parameters = array();
		$parameters['identifiant'] = $item['identifiant'];
		$parameters['periode_version'] = ($item['version'])? $item['periode'].'_'.$item['version'] : $item['periode'];
		?>
			<tr>
				<td><a href="<?php echo url_for('drm_visualisation', $parameters) ?>"><?php echo $item['_id'] ?></a></td>
				<td><?php echo $item['periode'] ?></td>
				<td><?php echo $item['mode_de_saisie'] ?></td>
				<td><?php echo $item['declarant']['raison_sociale'] ?></td>
				<td><?php echo $item['declarant']['nom'] ?></td>
				<td><?php echo $item['identifiant_ivse'] ?></td>
				<?php 
				if ($produits):
					$total_debut_mois = 0;
					$total_entrees_nettes = 0;
					$total_entrees_reciproque = 0;
					$total_sorties_nettes = 0;
					$total_sorties_reciproque = 0;
					$total = 0;
					foreach ($produits as $produit) {
						$node = null;
						foreach (explode('.', $produit) as $v) { 
							if ($node) {
								if (isset($node[$v])) {
									$node = $node[$v];
								} else {
									$node = null;
								}
							} else {
								if (isset($item[$v])) {
									$node = $item[$v];
								} else {
									$node = null;
								}
							}
						}
						if ($node) {
							$total_debut_mois += $node['total_debut_mois'];
							$total_entrees_nettes += $node['total_entrees_nettes'];
							$total_entrees_reciproque += $node['total_entrees_reciproque'];
							$total_sorties_nettes += $node['total_sorties_nettes'];
							$total_sorties_reciproque += $node['total_sorties_reciproque'];
							$total += $node['total'];
						}
					}
				else:
					$total_debut_mois = $item['declaration']['total_debut_mois'];
					$total_entrees_nettes = $item['declaration']['total_entrees_nettes'];
					$total_entrees_reciproque = $item['declaration']['total_entrees_reciproque'];
					$total_sorties_nettes = $item['declaration']['total_sorties_nettes'];
					$total_sorties_reciproque = $item['declaration']['total_sorties_reciproque'];
					$total = $item['declaration']['total'];
				endif; 
				?>
				<td><?php echo number_format($total_debut_mois, 2, ',', ' ') ?></td>
				<td><?php echo number_format($total_entrees_nettes, 2, ',', ' ') ?></td>
				<td><?php echo number_format($total_entrees_reciproque, 2, ',', ' ') ?></td>
				<td><?php echo number_format($total_sorties_nettes, 2, ',', ' ') ?></td>
				<td><?php echo number_format($total_sorties_reciproque, 2, ',', ' ') ?></td>
				<td><?php echo number_format($total, 2, ',', ' ') ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>