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
				<p>Type : <?php echo ucfirst($vrac->vendeur->famille) ?> <?php echo ucfirst($vrac->vendeur->sous_famille) ?></p>
				<p>Raison sociale : <?php echo $vrac->vendeur->raison_sociale; ?></p>
				<p>Nom commercial : <?php echo $vrac->vendeur->nom; ?></p>
				<p>N° RCS / SIRET : <?php echo $vrac->vendeur->siret ?></p>
				<p>N° CVI / EVV : <?php echo $vrac->vendeur->cvi ?></p>
				<p>N° accises / EA : <?php echo $vrac->vendeur->num_accise ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->vendeur->adresse ?> <?php echo $vrac->vendeur->code_postal ?> <?php echo $vrac->vendeur->commune ?><br /><?php echo $vrac->vendeur->pays ?></p>
				<p>Tel : <?php echo $vrac->vendeur->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->vendeur->fax ?></p>
				<?php if ($vrac->hasAdresseStockage()): ?>
				<br />
				<p>Adresse de stockage : <?php echo $vrac->adresse_stockage->libelle ?></p>
				<?php if ($vrac->adresse_stockage->exist('siret')): ?><p>Siret : <?php echo $vrac->adresse_stockage->siret ?></p><?php endif; ?>
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
				<p>N° RCS / SIRET : <?php echo $vrac->mandataire->siret ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->mandataire->adresse ?> <?php echo $vrac->mandataire->code_postal ?> <?php echo $vrac->mandataire->commune ?><br /><?php echo $vrac->mandataire->pays ?></p>
				<p>Tel : <?php echo $vrac->mandataire->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->mandataire->fax ?></p>
				<?php if ($vrac->valide->date_validation_mandataire): ?>
				<br />
				<p>Signé le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_validation_mandataire)); ?>, sur Déclarvins</p>
				<?php endif; ?>
			</td>
			<?php endif; ?>
			<td width="<?php echo $w ?>" valign="top">
				<h2>Acheteur</h2>
				<p>Type : <?php echo ucfirst($vrac->acheteur->famille) ?> <?php echo ucfirst($vrac->acheteur->sous_famille) ?></p>
				<p>Raison sociale : <?php echo $vrac->acheteur->raison_sociale; ?></p>
				<p>Nom commercial : <?php echo $vrac->acheteur->nom; ?></p>
				<p>N° RCS / SIRET : <?php echo $vrac->acheteur->siret ?></p>
				<p>N° CVI / EVV : <?php echo $vrac->acheteur->cvi ?></p>
				<p>N° accises / EA : <?php echo $vrac->acheteur->num_accise ?></p>
				<p>Adresse :</p>
				<p><?php echo $vrac->acheteur->adresse ?><br /><?php echo $vrac->acheteur->code_postal ?> <?php echo $vrac->acheteur->commune ?><br /><?php echo $vrac->acheteur->pays ?></p>
				<p>Tel : <?php echo $vrac->acheteur->telephone ?>&nbsp;&nbsp;&nbsp;Fax : <?php echo $vrac->acheteur->fax ?></p>
				<?php if ($vrac->hasAdresseLivraison()): ?>
				<br />
				<p>Adresse de livraison : <?php echo $vrac->adresse_livraison->libelle ?></p>
				<?php if ($vrac->adresse_livraison->exist('siret')): ?><p>Siret : <?php echo $vrac->adresse_livraison->siret ?></p><?php endif; ?>
				<p><?php echo $vrac->adresse_livraison->adresse ?> <?php echo $vrac->adresse_livraison->code_postal ?> <?php echo $vrac->adresse_livraison->commune ?><br /><?php echo $vrac->adresse_livraison->pays ?></p>
				<?php endif; ?>
				<?php if ($vrac->valide->date_validation_acheteur): ?>
				<br />
				<p>Signé le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_validation_acheteur)); ?>, sur Déclarvins</p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<h2>Produit / Qualité / Origine</h2>
	<p>Contrat de <?php echo $configurationVrac->formatTypesTransactionLibelle(array($vrac->type_transaction)); ?> de <?php echo ($vrac->produit)? $vrac->getLibelleProduit("%a% %l% %co% %ce%") : null; ?>&nbsp;<?php echo ($vrac->millesime)? $vrac->millesime.'&nbsp;' : ''; ?></p>
	<p>Mention(s) : <?php echo ($vrac->getLibellesMentions())? $configurationVrac->formatMentionsLibelle($vrac->getLibellesMentions()) : '-'; ?></p>
	<p>Certification(s)/Label(s)  : <?php echo ($vrac->labels)? $configurationVrac->formatLabelsLibelle(array($vrac->labels)) : ($vrac->labels_arr)? str_replace('Autre', $vrac->labels_libelle_autre, $configurationVrac->formatLabelsLibelle($vrac->labels_arr)) : '-'; ?></p>
	<h2>Type de contrat</h2>
	<p><?php if ($vrac->reference_contrat_pluriannuel): ?>Contrat adossé à un contrat pluriannuel, référence <?php echo $vrac->reference_contrat_pluriannuel ?><?php else: ?>Contrat ponctuel<?php endif; ?></p>
	<h2>Spécificités du contrat</h2>
	<p>Condition particulière : <?php echo $configurationVrac->formatCasParticulierLibelle(array($vrac->cas_particulier)); ?></p>
	<p>Expédition export : <?php echo ($vrac->export)? 'Oui' : 'Non'; ?></p>
	<?php if(!$vrac->isConditionneIr()): ?>
	<p>Première mise en marché : <?php echo ($vrac->premiere_mise_en_marche)? 'Oui' : 'Non'; ?></p>
	<?php endif; ?>
	<?php if($vrac->isConditionneCivp()): ?>
	<p>Entre bailleur et métayer : <?php echo ($vrac->bailleur_metayer)? 'Oui' : 'Non'; ?></p>
	<?php endif; ?>
	<?php if ($vrac->annexe): ?>
	<p>Présence d'une annexe technique : Oui</p>
	<?php endif; ?>
	<h2>Volume / Prix</h2>
	<table class="tableau_simple">
		<thead>
			<tr>
				<th><?php if($vrac->type_transaction == 'raisin'): ?>Quantité<?php else: ?>Volume<?php endif; ?> total<?php if($vrac->type_transaction == 'raisin'): ?>e<?php endif; ?></th>
				<th>Prix unitaire net HT hors cotisation</th>
				<?php if ($vrac->has_cotisation_cvo): ?>
				<th>Part cotisation payée par l'acheteur</th>
				<?php endif; ?>
				<th>Type de prix</th>
        	</tr>
        </thead>
        <tbody>
			<tr>
				<td><?php echoFloat($vrac->volume_propose) ?>&nbsp;<?php if($vrac->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></td>
				<td><?php echoFloat($vrac->prix_unitaire) ?> € HT / <?php if($vrac->type_transaction != 'raisin'): ?>HL<?php else: ?>Kg<?php endif;?></td>
				<?php if ($vrac->has_cotisation_cvo && $vrac->type_transaction == 'vrac'): ?>
				<td><?php echoFloat($vrac->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR) ?>  € HT / HL</td>
				<?php endif; ?>
				<td><?php echo $configurationVrac->formatTypesPrixLibelle(array($vrac->type_prix)); ?></td>
			</tr>
        </tbody>
    </table>
    <?php if ($vrac->determination_prix_date): ?><p>Date de détermination du prix : <?php echo Date::francizeDate($vrac->determination_prix_date) ?></p><?php endif; ?>
	<?php if ($vrac->determination_prix): ?><p>Mode de determination du prix : <?php echo $vrac->determination_prix ?></p><?php endif; ?>
	<p>Paiement : <?php echo $configurationVrac->formatConditionsPaiementLibelle(array($vrac->conditions_paiement)); ?></p>
	<?php if (count($vrac->paiements) > 0): ?>
	<p>Echéancier de paiements : </p>
	<table class="tableau_simple">
		<thead>
			<tr>
				<th>Date</th>
				<th>Montant (€ HT)</th>
        	</tr>
        </thead>
        <tbody>
			<?php foreach ($vrac->paiements as $paiement): ?>
			<tr>
				<td><?php echo Date::francizeDate($paiement->date) ?></td>
				<td><?php if ($paiement->montant) {echoFloat($paiement->montant);} ?> €</td>
			</tr>
			<?php endforeach; ?>
        </tbody>
    </table>
	<?php endif; ?>
	<?php if(!is_null($vrac->delai_paiement)): ?>
	<p>Delai de paiement : <?php echo $configurationVrac->formatDelaisPaiementLibelle(array(str_replace('autre', $vrac->delai_paiement_autre, $vrac->delai_paiement))) ?></p>
	<?php endif; ?>
	<h2>Mode et date de retiraison / livraison</h2>
	<?php if (!$vrac->isConditionneIvse()): ?><p>Le vin sera <?php echo ($vrac->vin_livre == VracClient::STATUS_VIN_LIVRE)? 'livré' : 'retiré'; ?><?php if($vrac->type_retiraison): ?> : <?php echo $configurationVrac->formatTypesRetiraisonLibelle(array($vrac->type_retiraison)) ?><?php endif; ?></p><?php endif; ?>
	<?php if($vrac->date_debut_retiraison): ?>
	<p>Date de début de retiraison : <?php echo Date::francizeDate($vrac->date_debut_retiraison) ?></p>
	<?php endif; ?>
	<p>Date limite de retiraison : <?php echo Date::francizeDate($vrac->date_limite_retiraison) ?></p>
	<?php if(!is_null($vrac->clause_reserve_retiraison)): ?>
	<p>Clause de reserve de propriété : <?php echo ($vrac->clause_reserve_retiraison)? 'Oui' : 'Non'; ?></p>
	<?php endif; ?>
	<?php if ($vrac->exist('observations') && $vrac->observations): ?>
	<p>Observations : <?php echo $vrac->observations ?></p>
	<?php endif; ?>
	<?php if ($vrac->has_transaction): ?>
	<hr />
	<h2>Descriptif des lots</h2>
	<?php $item = 1; foreach ($vrac->lots as $lot): ?>
	<div id="lots">
	<table>
	<tr>
		<th rowspan="5" class="num_lot">Lot n° <?php echo $lot->numero ?></th>
		<th rowspan="2" class="cuves">Cuves</th>
		<th>N° des cuves</th>
		<th>Volume</th>
		<th>Date de retiraison</th>
	</tr>
	<?php $i=1; ?>
	<?php foreach ($lot->cuves as $cuve): ?>
	<tr class="<?php if($i==sizeof($lot->cuves)) echo 'der_cat'; ?>">
		<td><?php echo $cuve->numero ?></td>
		<td><?php if ($cuve->volume) {echoLongFloat($cuve->volume);} ?>&nbsp;hl</td>
		<td><?php echo Date::francizeDate($cuve->date) ?></td>
	</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
	<?php if($lot->assemblage): ?>
	<tr class="der_cat">
		<th class="degre">Assemblage de millésimes</th>
		<td colspan="3">
		<?php $j=0; foreach ($lot->millesimes as $millesime): ?>
		<?php echo $millesime->annee ?> (<?php echo $millesime->pourcentage ?>%)
		<?php if ($j < (sizeof($lot->millesimes) - 1)): ?> - <?php endif; ?>
		<?php $j++; endforeach; ?>
		</td>
	</tr>
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
	</table>
	</div>
	<?php if ($item%5 == 0) {echo "<hr />"; } $item++; endforeach; ?>
	<?php endif; ?>
	<hr />
	<h2>Clauses</h2>
	<div class="clauses">
	<?php foreach ($configurationVrac->clauses as $clause): ?>
    <h3><?= $clause['nom'] ?></h3>
    <p><?= $clause['description'] ?></p>
	<?php endforeach; ?>
	</div>
	<?php if($vrac->clauses_complementaires): ?>
	<h2>Clauses complémentaires</h2>
	<div class="clauses">
	<?php foreach (explode(',', $vrac->clauses_complementaires) as $cc): $clause = $configurationVrac->clauses_complementaires->get($cc) ?>
    <h3><?= $clause['nom'] ?></h3>
    <p><?= $clause['description'] ?></p>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<?php if ($vrac->autres_conditions): ?>
	<h2>Autres conditions</h2>
	<div class="clauses">
	<?php echo $vrac->autres_conditions ?>
	</div>
	<?php endif; ?>

</body>
</html>
