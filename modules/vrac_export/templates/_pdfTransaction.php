<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
	<title>Vrac | Vins de Provence</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Actualys" />
	<meta name="Description" content="" /> 
	<meta name="Keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta name="Content-Language" content="fr-FR" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="copyright" content="Vins de Provence - 2011" />

	<?php include_partial('drm_export/pdfCss') ?>
</head>

<body>
	<script type="text/php">
		if (isset($pdf)) {
			$w = $pdf->get_width();
			$h = $pdf->get_height();
			$font = Font_Metrics::get_font("helvetica");
			$pdf->page_text($w / 2 - 4, $h - 13, "f {PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(0,0,0));
		}
	</script>
	<?php include_partial('vrac_export/pdfTransactionHeader', array('vrac' => $vrac)); ?>
	<?php include_partial('vrac_export/pdfFooter'); ?>
	<h2>Soussignes</h2>
	<?php  if ($configurationVrac->transaction_has_acheteur) $w = '50%'; else $w = '100%'; ?>
	<table class="bloc_bottom" width="100%">
		<tr>
			<td width="<?php echo $w ?>">
				<h2>Vendeur</h2>
				<p>Type : <?php echo $vrac->vendeur_type ?></p>
				<p>Raison sociale : <?php echo ($vrac->vendeur->raison_sociale)? $vrac->vendeur->raison_sociale : $vrac->vendeur->nom; ?></p>
				<p>N° RCS/SIRET : <?php echo $vrac->vendeur->siret ?></p>
				<p>N° CVI/EVV : <?php echo $vrac->vendeur->cvi ?></p>
				<p>Adresse : <?php echo $vrac->vendeur->adresse ?></p>
				<p>Code postal : <?php echo $vrac->vendeur->code_postal ?></p>
				<p>Commune : <?php echo $vrac->vendeur->commune ?></p>
				<p>Tel : <?php echo $vrac->vendeur->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->vendeur->fax ?></p>
				<?php if ($vrac->hasAdresseStockage()): ?>
				<br />
				<p>Adresse de stockage : <?php echo $vrac->adresse_stockage->libelle ?></p>
				<p>Adresse : <?php echo $vrac->adresse_stockage->adresse ?></p>
				<p>Code postal : <?php echo $vrac->adresse_stockage->code_postal ?></p>
				<p>Commune : <?php echo $vrac->adresse_stockage->commune ?></p>
				<?php endif; ?>
			</td>
			<?php if ($configurationVrac->transaction_has_acheteur): ?>
			<td width="<?php echo $w ?>">
				<h2>Acheteur</h2>
				<p>Type : <?php echo $vrac->acheteur_type ?></p>
				<p>Raison sociale : <?php echo ($vrac->acheteur->raison_sociale)? $vrac->acheteur->raison_sociale : $vrac->acheteur->nom; ?></p>
				<p>N° RCS/SIRET : <?php echo $vrac->acheteur->siret ?></p>
				<p>N° CVI/EVV : <?php echo $vrac->acheteur->cvi ?></p>
				<p>Adresse : <?php echo $vrac->acheteur->adresse ?></p>
				<p>Code postal : <?php echo $vrac->acheteur->code_postal ?></p>
				<p>Commune : <?php echo $vrac->acheteur->commune ?></p>
				<p>Tel : <?php echo $vrac->acheteur->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->acheteur->fax ?></p>
				<?php if ($vrac->hasAdresseLivraison()): ?>
				<br />
				<p>Adresse de livraison : <?php echo $vrac->adresse_livraison->libelle ?></p>
				<p>Adresse : <?php echo $vrac->adresse_livraison->adresse ?></p>
				<p>Code postal : <?php echo $vrac->adresse_livraison->code_postal ?></p>
				<p>Commune : <?php echo $vrac->adresse_livraison->commune ?></p>
				<?php endif; ?>
			</td>
			<?php endif; ?>
		</tr>
	</table>
	<h2>Produit</h2>
	<table>
		<tr>
			<td><?php echo ($vrac->produit)? $vrac->getLibelleProduit("%a% %l% %co% %ce%") : null; ?></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo ($vrac->millesime)? $vrac->millesime.'&nbsp;&nbsp;' : ''; ?></td>
			<td></td>
			<td>Expédition export : <?php echo ($vrac->export)? 'Oui' : 'Non'; ?></td>
		</tr>
	</table>
	<?php if ($vrac->has_transaction): ?>
	<h2>Descriptif des lots</h2>
	<p>
		<table>
			<tr>
				<td>Numéro</td>
				<td>Cuve(s)</td>
				<td>Volume</td>
				<td>Date de retiraison</td>
				<td>Assemblage</td>
				<td>Degrés</td>
				<td>Allergènes</td>
			</tr>
			<?php foreach ($vrac->lots as $lot): ?>
			<tr>
				<td><?php echo $lot->numero ?></td>
				<td>
					<ul>
					<?php foreach ($lot->cuves as $cuve): ?>
					<li>
						<?php echo $cuve->numero ?> - <?php echo $cuve->volume ?>&nbsp;HL - <?php echo $cuve->date ?>
					</li>
					<?php endforeach; ?>
					</ul>
				</td>
				<td>
					<?php if($lot->assemblage): ?>
					<ul>
					<?php foreach ($lot->millesimes as $millesime): ?>
					<li>
						<?php echo $millesime->annee ?> - <?php echo $millesime->pourcentage ?>&nbsp;%
					</li>
					<?php endforeach; ?>
					</ul>
					<?php else: ?>
					Pas d'assemblage
					<?php endif; ?>
				</td>
				<td><?php echo $lot->degre ?></td>
				<td><?php echo ($lot->presence_allergenes)? 'Oui' : 'Non'; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</p>
	<?php endif; ?>
	<p>Volume total : <?php echo $vrac->volume_propose ?>&nbsp;HL</p>
	<p>Observations : <?php echo $vrac->commentaires ?></p>
	<h2>Informations complémentaires</h2>
	<?php echo $configurationVrac->getInformationsComplementaires(ESC_RAW) ?>
</body>
</html>
