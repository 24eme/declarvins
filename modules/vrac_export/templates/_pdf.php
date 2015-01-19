<?php use_helper('Float') ?>

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

	<?php include_partial('vrac_export/pdfCss') ?>
</head>

<body>
	<script type="text/php">
		if (isset($pdf)) {
			$w = $pdf->get_width();
			$h = $pdf->get_height();
			$font = Font_Metrics::get_font("helvetica");
			$pdf->page_text($w / 2 - 4, $h - 13, "{PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(0,0,0));
		}
	</script>
	<?php include_partial('vrac_export/pdfHeader', array('vrac' => $vrac, 'configurationVrac' => $configurationVrac)); ?>
	<?php include_partial('vrac_export/pdfFooter'); ?>
	<h2>Soussignes</h2>
	<?php if($vrac->mandataire_exist) $w = '33%'; else $w = '50%'; ?>
	<table class="bloc_bottom" width="100%">
		<tr>
			<td width="<?php echo $w ?>" valign="top">
				<h2>Vendeur</h2>
				<p>Type : <?php echo $vrac->vendeur_type ?></p>
				<p>Raison sociale : <?php echo $vrac->vendeur->raison_sociale; ?></p>
				<p>Nom commercial : <?php echo $vrac->vendeur->nom; ?></p>
				<p>N° RCS/SIRET : <?php echo $vrac->vendeur->siret ?></p>
				<p>N° CVI/EVV : <?php echo $vrac->vendeur->cvi ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->vendeur->adresse ?> <?php echo $vrac->vendeur->code_postal ?> <?php echo $vrac->vendeur->commune ?><br /><?php echo $vrac->vendeur->pays ?></p>
				<p>Email : <?php echo $vrac->vendeur->email ?></p>
				<p>Tel : <?php echo $vrac->vendeur->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->vendeur->fax ?></p>
				<?php if ($vrac->hasAdresseStockage()): ?>
				<br />
				<p>Adresse de stockage : <?php echo $vrac->adresse_stockage->libelle ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->adresse_stockage->adresse ?> <?php echo $vrac->adresse_stockage->code_postal ?> <?php echo $vrac->adresse_stockage->commune ?><br /><?php echo $vrac->adresse_stockage->pays ?></p>
				<?php endif; ?>
				<?php if ($vrac->valide->date_validation_vendeur): ?>
				<br />
				<p>Signé le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_validation_vendeur)); ?>, sur Déclarvins</p>
				<?php endif; ?>
			</td>
			<?php if($vrac->mandataire_exist): ?>
			<td width="<?php echo $w ?>" valign="top">
				<h2>Courtier</h2>
				<p>Raison sociale : <?php echo $vrac->mandataire->raison_sociale; ?></p>
				<p>Nom commercial : <?php echo $vrac->mandataire->nom; ?></p>
				<p>N° Carte professionnelle : <?php echo $vrac->mandataire->no_carte_professionnelle ?></p>
				<p>N° RCS/SIRET : <?php echo $vrac->mandataire->siret ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->mandataire->adresse ?> <?php echo $vrac->mandataire->code_postal ?> <?php echo $vrac->mandataire->commune ?><br /><?php echo $vrac->mandataire->pays ?></p>
				<p>Email : <?php echo $vrac->mandataire->email ?></p>
				<p>Tel : <?php echo $vrac->mandataire->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->mandataire->fax ?></p>
				<?php if ($vrac->valide->date_validation_mandataire): ?>
				<br />
				<p>Signé le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_validation_mandataire)); ?>, sur Déclarvins</p>
				<?php endif; ?>
			</td>
			<?php endif; ?>
			<td width="<?php echo $w ?>" valign="top">
				<h2>Acheteur</h2>
				<p>Type : <?php echo $vrac->acheteur_type ?></p>
				<p>Raison sociale : <?php echo $vrac->acheteur->raison_sociale; ?></p>
				<p>Nom commercial : <?php echo $vrac->acheteur->nom; ?></p>
				<p>N° RCS/SIRET : <?php echo $vrac->acheteur->siret ?></p>
				<p>N° CVI/EVV : <?php echo $vrac->acheteur->cvi ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->acheteur->adresse ?><br /><?php echo $vrac->acheteur->code_postal ?> <?php echo $vrac->acheteur->commune ?><br /><?php echo $vrac->acheteur->pays ?></p>
				<p>Email : <?php echo $vrac->acheteur->email ?></p>
				<p>Tel : <?php echo $vrac->acheteur->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->acheteur->fax ?></p>
				<?php if ($vrac->hasAdresseLivraison()): ?>
				<br />
				<p>Adresse de livraison : <?php echo $vrac->adresse_livraison->libelle ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->adresse_livraison->adresse ?> <?php echo $vrac->adresse_livraison->code_postal ?> <?php echo $vrac->adresse_livraison->commune ?><br /><?php echo $vrac->adresse_livraison->pays ?></p>
				<?php endif; ?>
				<?php if ($vrac->valide->date_validation_acheteur): ?>
				<br />
				<p>Signé le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_validation_acheteur)); ?>, sur Déclarvins</p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<h2>Produit</h2>
	<p><?php echo $configurationVrac->formatTypesTransactionLibelle(array($vrac->type_transaction)); ?>, <?php echo ($vrac->produit)? $vrac->getLibelleProduit("%a% %l% %co% %ce%") : null; ?>&nbsp;<?php echo ($vrac->millesime)? $vrac->millesime.'&nbsp;' : ''; ?><p>
	<p><?php echo ($vrac->labels)? $configurationVrac->formatLabelsLibelle(array($vrac->labels)).'&nbsp;' : ''; ?><?php echo (count($vrac->mentions) > 0)? $configurationVrac->formatMentionsLibelle($vrac->mentions) : ''; ?></p>
	<p>Annexe technique : <?php echo ($vrac->annexe)? 'Oui' : 'Non'; ?>, Export : <?php echo ($vrac->export)? 'Oui' : 'Non'; ?></p>

	<h2>Volume / Prix</h2>
	
	<table class="tableau_simple">
		<thead>
			<tr>
				<th>Volume total (hl)</th>
				<th>Prix unitaire net HT hors cotisation</th>
				<?php if ($vrac->has_cotisation_cvo): ?>
				<th>Part cotisation payée par l'acheteur</th>
				<?php endif; ?>
				<th>Type de prix</th>
        	</tr>
        </thead>
        <tbody>
			<tr>
				<td><?php echoLongFloat($vrac->volume_propose) ?> hl</td>
				<td><?php echoFloat($vrac->prix_unitaire) ?> €/hl</td>
				<?php if ($vrac->has_cotisation_cvo): ?>
				<td><?php echoFloat($vrac->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR) ?> €/hl</td>
				<?php endif; ?>
				<td><?php echo $configurationVrac->formatTypesPrixLibelle(array($vrac->type_prix)); ?></td>
			</tr>
        </tbody>
    </table>

	<?php if ($vrac->determination_prix): ?><p>Mode de determination : <?php echo $vrac->determination_prix ?></p><?php endif; ?>
	

	<h2>Conditions</h2>
	
	<?php if ($vrac->isConditionneIvse()): ?><p><strong>De retiraison :</strong></p><?php endif; ?>
	<p>
		<?php echo ($vrac->date_debut_retiraison)? 'Date de début de retiraison : '.Date::francizeDate($vrac->date_debut_retiraison).'&nbsp;&nbsp;' : ''; ?>
		<?php echo ($vrac->date_limite_retiraison)? 'Date limite de retiraison : '.Date::francizeDate($vrac->date_limite_retiraison) : ''; ?>
	</p>

	<?php if ($vrac->isConditionneIvse()): ?>
	<br /><p><strong>De paiement :</strong></p>
	<?php echo $configurationVrac->formatConditionsPaiementLibelle(array($vrac->conditions_paiement)); ?>&nbsp;&nbsp;<?php echo ($vrac->reference_contrat_pluriannuel)? 'Référence contrat : '.$vrac->reference_contrat_pluriannuel : ''; ?>
	<?php endif; ?>
	<?php if (count($vrac->paiements) > 0): ?>
	<p>Echéancier : </p>
	
	<table class="tableau_simple">
		<thead>
			<tr>
				<th>Date</th>
				<th>Volume (hl)</th>
				<th>Montant (€ HT)</th>
        	</tr>
        </thead>
        <tbody>
			<?php foreach ($vrac->paiements as $paiement): ?>
			<tr>
				<td><?php echo Date::francizeDate($paiement->date) ?></td>
				<td><?php if ($paiement->volume) {echoLongFloat($paiement->volume);} ?> hl</td>
				<td><?php if ($paiement->montant) {echoFloat($paiement->montant);} ?> €</td>
			</tr>
			<?php endforeach; ?>
        </tbody>
    </table>

	<?php endif; ?>
	<?php if ($vrac->isConditionneIvse()): ?><br /><br /><p><strong>Générales :</strong></p><?php endif; ?>
	<p>
		<?php if(!is_null($vrac->clause_reserve_retiraison)): ?>Propriété (réserve)<br /><?php endif; ?>
		<?php if (!$vrac->isConditionneIvse()): ?>Conditions Générales de Vente : <?php echo $configurationVrac->formatConditionsPaiementLibelle(array($vrac->conditions_paiement)); ?>&nbsp;&nbsp;<?php echo ($vrac->reference_contrat_pluriannuel)? 'Référence contrat : '.$vrac->reference_contrat_pluriannuel : ''; ?><br /><?php endif; ?>
		
	</p>
	<p>
		<?php if(!is_null($vrac->delai_paiement)): ?>
		Delai de paiement : <?php echo $configurationVrac->formatDelaisPaiementLibelle(array($vrac->delai_paiement)) ?><br />
		<?php endif; ?>
		<?php if (!$vrac->isConditionneIvse()): ?>
		Le vin sera <?php echo ($vrac->vin_livre == VracClient::STATUS_VIN_LIVRE)? 'livré' : 'retiré'; ?><br /><br />
		<?php endif; ?>
		Autres observations : <?php echo $vrac->commentaires ?>
	</p>
	<h2><?php if ($vrac->isConditionneIvse()): ?>Conditions générales de vente<?php else: ?>Clauses<?php endif; ?></h2>
	<div class="clauses">
	<?php echo $configurationVrac->getClauses(ESC_RAW) ?>
	</div>
	<?php if ($vrac->has_transaction): ?>
	<hr />
	<h2>Descriptif des lots</h2>

	<div id="lots">


		<table>
			<?php foreach ($vrac->lots as $lot): ?>
			<?php
				$nb_cuves = sizeof($lot->cuves);
				$nb_millesimes = 0;
				if($lot->assemblage) $nb_millesimes = sizeof($lot->millesimes);
			?>
			<?php $nb_lignes = 3 + $nb_cuves ?>
			<?php if($nb_millesimes > 0) $nb_lignes += 1 + $nb_millesimes; ?>
			<tr>
				<th rowspan="<?php echo $nb_lignes; ?>" class="num_lot">Lot n° <?php echo $lot->numero ?></th>
				<th rowspan="<?php echo 1 + $nb_cuves; ?>" class="cuves">Cuves</th>
				<th>N°</th>
				<th>Volume (hl)</th>
				<th>Date de retiraison</th>
			</tr>

			<?php $i=1; ?>
			<?php foreach ($lot->cuves as $cuve): ?>
			<tr class="<?php if($i==$nb_cuves) echo 'der_cat'; ?>">
				<td><?php echo $cuve->numero ?></td>
				<td><?php if ($cuve->volume) {echoLongFloat($cuve->volume);} ?> hl</td>
				<td><?php echo Date::francizeDate($cuve->date) ?></td>
			</tr>
			<?php $i++; ?>
			<?php endforeach; ?>

			<?php if($lot->assemblage): ?>
			<tr>
				<th rowspan="<?php echo 1 + $nb_millesimes ?>" class="millesimes">Assemblage de millésimes</th>
				<th>Année</th>
				<th class="pourcentage">Pourcentage</th>
				<th></th>
			</tr>

			<?php $i=1; ?>
			<?php foreach ($lot->millesimes as $millesime): ?>
			<tr class="<?php if($i==$nb_millesimes) echo 'der_cat'; ?>">
				<td><?php echo $millesime->annee ?></td>
				<td class="pourcentage"><?php echo $millesime->pourcentage ?> %</td>
				<td></td>
			</tr>
			<?php $i++; ?>
			<?php endforeach; ?>

			<?php endif; ?>

			<tr class="der_cat">
				<th class="degre">Degré</th>
				<td><?php echo $lot->degre ?></td>
				<td colspan="2"></td>
			</tr>
			<tr class="dernier">
				<th class="allergenes">Allergènes</th>
				<td><?php echo ($lot->presence_allergenes)? 'Oui' : 'Non'; ?></td>
				<td colspan="2"></td>
			</tr>
			<?php endforeach; ?>
		</table>

	</div>
	<?php if ($configurationVrac->getInformationsComplementaires()): ?>
	<h2>Informations complémentaires</h2>
	<div class="clauses">
	<?php echo $configurationVrac->getInformationsComplementaires(ESC_RAW) ?>
	</div>
	<?php endif; ?>
	<?php endif; ?>
</body>
</html>
