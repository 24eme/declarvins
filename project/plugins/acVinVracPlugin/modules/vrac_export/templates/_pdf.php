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
	<?php if (count($vrac->getMentions())): ?>
		<?php foreach ($vrac->getMentions()->getRawValue()->toArray() as $mention): ?>
			<?php if ($mention == 'chdo'): echo "<p>Le vendeur autorise expressément l'Acheteur à utiliser son nom d'exploitation. Ce dernier devra être indiqué sur la facture et le document d'accompagnement. L'Acheteur devra respecter les exigences du décret n° 2012-655 du 4 mai 2012.</p>"; endif; ?>
			<?php if ($mention == 'marque'): echo "<p>Le vendeur autorise expressément l'Acheteur à utiliser sa marque.</p>"; endif; ?>
		<?php endforeach ?>
	<?php endif; ?>
	<p>Certification(s)/Label(s) : <?php echo ($vrac->labels)? $configurationVrac->formatLabelsLibelle(array($vrac->labels)) : ($vrac->labels_arr)? $configurationVrac->formatLabelsLibelle($vrac->getLibellesLabels()) : '-'; ?></p>
	<h2>Type de contrat</h2>
	<p><?php if ($vrac->isAdossePluriannuel()): ?>Contrat adossé au contrat pluriannuel cadre n°<?php echo $vrac->reference_contrat_pluriannuel ?><?php elseif($vrac->contrat_pluriannuel): ?>Contrat pluriannuel<?php else: ?>Contrat ponctuel<?php endif; ?></p>
    <?php if ($vrac->pluriannuel_campagne_debut && $vrac->pluriannuel_campagne_fin): ?><p>Campagnes d'application de <?php echo $vrac->pluriannuel_campagne_debut ?> à <?php echo $vrac->pluriannuel_campagne_fin ?></p><?php endif; ?>
    <?php if ($vrac->isPluriannuel() && $vrac->pluriannuel_prix_plancher && $vrac->pluriannuel_prix_plafond): ?><p>Fourchette de prix entre <?php echo $vrac->pluriannuel_prix_plancher ?> et <?php echo $vrac->pluriannuel_prix_plafond ?> <?php if($vrac->type_transaction == 'raisin'): ?>€ HT / Kg<?php else: ?>€ HT / HL<?php endif; ?></p><?php endif; ?>
    <?php if ($vrac->pluriannuel_clause_indexation): ?><p>Indexation du prix : <?php echo $vrac->pluriannuel_clause_indexation ?></p><?php endif; ?>
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
                <?php if($vrac->pourcentage_recolte): ?>
                <th>Pourcentage de récolte</th>
                <?php endif; ?>
                <?php if($vrac->surface): ?>
                <th>Surface concernée</th>
                <?php endif; ?>
				<th><?php if($vrac->type_transaction == 'raisin'): ?>Quantité<?php else: ?>Volume<?php endif; ?> total<?php if($vrac->type_transaction == 'raisin'): ?>e<?php endif; ?></th>
                <?php if ($vrac->prix_unitaire): ?>
                <th>Prix unitaire net HT hors cotisation</th>
				<?php if ($vrac->has_cotisation_cvo && $vrac->premiere_mise_en_marche && $vrac->type_transaction == 'vrac'): ?>
				<th>Part cotisation (50%)</th>
				<?php endif; ?>
				<th>Type de prix</th>
                <?php endif; ?>
        	</tr>
        </thead>
        <tbody>
			<tr>
                <?php if($vrac->pourcentage_recolte): ?>
                <td><?php echo $vrac->pourcentage_recolte ?>%</td>
                <?php endif; ?>
                <?php if($vrac->surface): ?>
                <td><?php echoFloat($vrac->surface) ?>&nbsp;HA</td>
                <?php endif; ?>
				<td><?php echoFloat($vrac->volume_propose) ?>&nbsp;<?php if($vrac->type_transaction == 'raisin'): ?>Kg<?php else: ?>HL<?php endif; ?></td>
                <?php if ($vrac->prix_unitaire): ?>
				<td><?php echoFloat($vrac->prix_unitaire) ?> € HT / <?php if($vrac->type_transaction != 'raisin'): ?>HL<?php else: ?>Kg<?php endif;?></td>
				<?php if ($vrac->has_cotisation_cvo && $vrac->premiere_mise_en_marche && $vrac->type_transaction == 'vrac'): ?>
				<td><?php echoFloat($vrac->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR) ?>  € HT / HL (*)</td>
				<?php endif; ?>
				<td><?php echo $configurationVrac->formatTypesPrixLibelle(array($vrac->type_prix)); ?></td>+
				<?php endif; ?>
			</tr>
        </tbody>
    </table>
    <p>(*) Valeur indicative. Le taux CVO qui s’appliquera sera celui en vigueur au moment de la retiraison.</p>
    <?php if ($vrac->determination_prix_date): ?><p>Date de détermination du prix : <?php echo Date::francizeDate($vrac->determination_prix_date) ?></p><?php endif; ?>
	<?php if ($vrac->determination_prix): ?><p>Mode de determination du prix : <?php echo $vrac->determination_prix ?></p><?php endif; ?>
	<?php if($vrac->conditions_paiement): ?>
		<p>Paiement : <?php echo $configurationVrac->formatConditionsPaiementLibelle(array($vrac->conditions_paiement)); ?></p>
	<?php endif; ?>
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
	<?php if ($vrac->isConditionneIr()||$vrac->isConditionneIvse()): ?>
        <?php if (!$vrac->dispense_acompte): ?>
            <p>Rappel : Acompte obligatoire d'au moins 15% dans les 10 jours suivants la signature du contrat. Si la facture est établie par l'acheteur, le délai commence à courir à compter de la date de livraison.</p>
        <?php else: ?>
            <p>Dérogation pour dispense d'acompte selon accord interprofessionnel</p>
        <?php endif; ?>
    <?php endif; ?>
	<?php endif; ?>
	<h2>Mode et date de retiraison / livraison</h2>
	<?php if (!$vrac->isConditionneIvse()): ?><p>Le vin sera <?php echo ($vrac->vin_livre == VracClient::STATUS_VIN_LIVRE)? 'livré' : 'retiré'; ?><?php if($vrac->type_retiraison): ?> : <?php echo $configurationVrac->formatTypesRetiraisonLibelle(array($vrac->type_retiraison)) ?><?php endif; ?></p><?php endif; ?>
	<?php if($vrac->date_debut_retiraison): ?>
	<p>Date de début de retiraison : <?php echo Date::francizeDate($vrac->date_debut_retiraison) ?></p>
	<?php endif; ?>
    <?php if($vrac->date_limite_retiraison): ?>
	<p>Date limite de retiraison : <?php echo Date::francizeDate($vrac->date_limite_retiraison) ?></p>
    <?php endif; ?>
	<?php if(!is_null($vrac->clause_reserve_retiraison)): ?>
	<p>Clause de reserve de propriété : <?php echo ($vrac->clause_reserve_retiraison)? 'Oui' : 'Non'; ?></p>
	<?php endif; ?>
	<?php if ($vrac->exist('observations') && $vrac->observations): ?>
	<p>Observations : <?php echo $vrac->observations ?></p>
	<?php endif; ?>

	<!-- CLAUSES -->
	<hr />
	<h2>Clauses</h2>
	<div class="clauses">
		<?php if ($vrac->isConditionneIvse()): ?>
		<table class="tableau_simple">
				<tbody>
				<?php if ($vrac->clauses->exist('force_majeure')): ?>
				<tr>
					<td colspan="3">
						<strong><?php echo $vrac->clauses->force_majeure->nom ?></strong><br />
						<?php echo $vrac->clauses->force_majeure->description ?>
					</td>
				</tr>
				<?php endif; ?>
				<?php if ($vrac->clauses->exist('resiliation')): ?>
					<tr>
						<td colspan="3">
							<strong><?php echo $vrac->clauses->resiliation->nom ?></strong><br />
							<?php echo $vrac->clauses->resiliation->description ?>
						</td>
					</tr>
					<tr>
						<td>
							Cas de résiliation
						</td>
							<td>
								Délai de préavis
							</td>
								<td>
									Indemnité
								</td>
					</tr>
					<tr>
						<td>
							<br />
							<?php echo $vrac->clause_resiliation_cas ?>
							<br />
						</td>
							<td>
								<br />
								<?php echo $vrac->clause_resiliation_preavis ?>
								<br />
							</td>
								<td>
									<br />
									<?php echo $vrac->clause_resiliation_indemnite ?>
									<br />
								</td>
					</tr>
				<?php endif; ?>
			</tr>
				</tbody>
		</table>
			<?php endif; ?>
	<?php foreach ($vrac->clauses as $k => $clause): ?>
		<?php if ($vrac->isConditionneIvse() && ($k=='resiliation'||$k=='force_majeure')): continue; endif; ?>
    <h3><?= $clause['nom'] ?></h3>
    <p>
        <?= $clause['description'] ?>
        <?php if ($k == 'liberte_contractuelle'): ?>
			<?php if (!$vrac->clause_initiative_contractuelle_producteur): ?>
			Non mais le présent contrat a été négocié dans le respect de la liberté contractuelle du producteur, ce dernier ayant pu faire valoir ses propositions préalablement à la signature du contrat et n\'ayant pas souhaité effectuer une proposition de contrat.
			<?php else: ?>
			Oui
			<?php endif ?>
		<?php endif ?>
    </p>
    <?php if ($k == 'resiliation'): ?>
    <?php if($vrac->clause_resiliation_cas||$vrac->isConditionneIvse()): ?><p>Cas de résiliation : <?php echo $vrac->clause_resiliation_cas ?></p><?php endif; ?>
    <?php if($vrac->clause_resiliation_preavis||$vrac->isConditionneIvse()): ?><p>Délai de préavis : <?php echo $vrac->clause_resiliation_preavis ?></p><?php endif; ?>
    <?php if($vrac->clause_resiliation_indemnite||$vrac->isConditionneIvse()): ?><p>Indemnité : <?php echo $vrac->clause_resiliation_indemnite ?></p><?php endif; ?>
    <?php endif ?>
	<?php endforeach; ?>
	</div>
	<?php if($vrac->clauses_complementaires): ?>
	<h2>Clauses complémentaires</h2>
	<div class="clauses">
	<?php foreach (explode(',', $vrac->clauses_complementaires) as $cc): $clause = $configurationVrac->clauses_complementaires->get($cc) ?>
    <h3><?= $clause['nom'] ?></h3>
    <p><?= $clause['description'] ?>
    <?php if ($cc == 'transfert_propriete' && $vrac->isConditionneIvse()): ?>
        <?php $complements = explode(',', $vrac->clauses_complementaires) ?>
        <?php if (!in_array('transfert_propriete', $complements)): ?>
        <input type="checkbox" checked="checked" />
        <?php else: ?>
        <input type="checkbox" />
        <?php endif ?>
    <?php endif ?>
    </p>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<?php if ($vrac->autres_conditions): ?>
	<h2>Autres conditions</h2>
	<div class="clauses">
	<?php echo $vrac->autres_conditions ?>
	</div>
	<?php endif; ?>
	<!-- LOTS -->
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
	<!-- Infos Complementaires -->
	<?php if ($configurationVrac->getInformationsComplementaires()): ?>
	<h2>Informations complémentaires</h2>
	<div class="clauses">
	<?php echo $configurationVrac->getInformationsComplementaires(ESC_RAW) ?>
	</div>
	<?php endif; ?>

</body>
</html>
