<div class="tableau_resultats">
	<table>
		<thead>
			<tr>
				<th>Visa</th>
				<th>Produit</th>
				<th>Label</th>
				<th>Mentions</th>
				<th>Volume</th>
				<th>Prix unitaire</th>
				<th>Saisie</th>
				<th>Vendeur</th>
				<th>Acheteur</th>
				<th>Courtier</th>
				<th>Statut</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		foreach ($hits as $hit): 
		$item = $hit->getData();
		$etab = null; 
		if ($acteur = $item['vous_etes']) {
			$acteur = $acteur.'_identifiant';
			if ($item[$acteur]) {
				$etab = $item[$acteur];
			}
		}
		?>
			<tr>
				<td>
					<?php if ($etab): ?>
						<?php if (in_array($item['valide']['statut'], array(VracClient::STATUS_CONTRAT_SOLDE, VracClient::STATUS_CONTRAT_NONSOLDE))): ?>
							<a href="<?php echo url_for("vrac_visualisation", array('numero_contrat' => $item['_id'], 'etablissement' => $etab)) ?>"><?php echo $item['_id'] ?></a>
						<?php else: ?>
							<a href="<?php echo url_for("vrac_validation", array('numero_contrat' => $item['numero_contrat'], 'etablissement' => $etab, 'acteur' => $item['vous_etes'])) ?>"><?php echo $item['_id'] ?></a>
						<?php endif; ?>
					<?php else: ?>
						<?php echo $item['_id'] ?>
					<?php endif; ?>
				</td>
				<td><?php echo $item['produit_libelle'] ?></td>
				<td><?php echo $item['labels_libelle'] ?></td>
				<td><?php echo $item['mentions_libelle'] ?></td>
				<td><?php echo $item['volume_propose'] ?></td>
				<td><?php echo $item['prix_unitaire'] ?></td>
				<td><?php echo $item['valide']['date_saisie'] ?></td>
				<td><?php echo $item['vendeur']['raison_sociale'] ?></td>
				<td><?php echo $item['acheteur']['raison_sociale'] ?></td>
				<td><?php echo $item['mandataire']['raison_sociale'] ?></td>
				<td><?php echo $item['valide']['statut'] ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
