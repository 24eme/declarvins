<?php use_helper('Vrac'); ?>
<div class="tableau_resultats vracs">
	<table class="visualisation_contrat">
		<thead>
			<tr>
				<th>Visa</th>
				<th>Produit</th>
				<th>Label</th>
				<th>Mentions</th>
				<th>Volume</th>
				<th>Prix unitaire</th>
				<th>Date de saisie</th>
				<th>Date de signature</th>
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
		$vendeur = $item['vendeur_identifiant'];
		$acheteur = $item['acheteur_identifiant'];;
		$courtier = $item['mandataire_identifiant'];;
		if ($acteur = $item['vous_etes']) {
			$acteur = $acteur.'_identifiant';
			if ($item[$acteur]) {
				$etab = $item[$acteur];
			}
		}
		?>
			<tr class="<?php echo statusColor($item['valide']['statut']) ?>">
				<td>
					<?php if ($etab): ?>
						<?php if (in_array($item['valide']['statut'], array(VracClient::STATUS_CONTRAT_SOLDE, VracClient::STATUS_CONTRAT_NONSOLDE))): ?>
							<a href="<?php echo url_for("vrac_visualisation", array('numero_contrat' => $item['_id'], 'etablissement' => $etab)) ?>" target="_blank"><?php echo $item['numero_contrat'] ?></a>
						<?php else: ?>
							<a href="<?php echo url_for("vrac_validation", array('numero_contrat' => $item['numero_contrat'], 'etablissement' => $etab, 'acteur' => $item['vous_etes'])) ?>" target="_blank"><?php echo $item['numero_contrat'] ?></a>
						<?php endif; ?>
					<?php else: ?>
						<?php echo $item['numero_contrat'] ?>
					<?php endif; ?>
				</td>
				<td><?php echo $item['produit_libelle'] ?></td>
				<td><?php echo $item['labels_libelle'] ?></td>
				<td><?php echo $item['mentions_libelle'] ?></td>
				<td><?php echo number_format($item['volume_propose'], 2, ',', ' ') ?></td>
				<td><?php echo number_format($item['prix_unitaire'], 2, ',', ' ') ?></td>
				<td><?php echo ($item['valide']['date_saisie'])? strftime('%d/%m/%Y', strtotime($item['valide']['date_saisie'])) : null; ?></td>
				<td><?php echo ($item['valide']['date_validation'])? strftime('%d/%m/%Y', strtotime($item['valide']['date_validation'])) : null; ?></td>
				<td>
					<?php if ($vendeur): ?>
						<a href="<?php echo url_for('tiers_mon_espace', array('identifiant' => $vendeur)) ?>" target="_blank"><?php echo $item['vendeur']['raison_sociale'] ?></a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td>
					<?php if ($acheteur): ?>
						<a href="<?php echo url_for('tiers_mon_espace', array('identifiant' => $acheteur)) ?>" target="_blank"><?php echo $item['acheteur']['raison_sociale'] ?></a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td>
					<?php if ($courtier): ?>
						<a href="<?php echo url_for('tiers_mon_espace', array('identifiant' => $courtier)) ?>" target="_blank"><?php echo $item['mandataire']['raison_sociale'] ?></a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td><span class="statut <?php echo statusColor($item['valide']['statut']) ?>" title="<?php echo $item['valide']['statut'] ?>"></span></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
