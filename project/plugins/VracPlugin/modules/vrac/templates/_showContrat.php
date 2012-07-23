<div>
	<h2>Soussignés</h2>
	<div>
		<span>Vendeur :</span>
		<span><?php echo $vrac->vendeur->nom ?></span>
	</div>
	<div>
		<span>Assujetti à la TVA :</span>
		<span><?php echo ($vrac->vendeur_tva)? 'oui' : 'non'; ?></span>
	</div>
	<div>
		<span>Acheteur :</span>
		<span><?php echo $vrac->acheteur->nom ?></span>
	</div>
	<div>
		<span>Assujetti à la TVA :</span>
		<span><?php echo ($vrac->acheteur_tva)? 'oui' : 'non'; ?></span>
	</div>
	<?php if($vrac->mandataire_exist): ?>
	<div>
		<span>Mandataire :</span>
		<span><?php echo $vrac->mandataire->nom ?></span>
	</div>
	<?php else: ?>
	<div>
		<span>Ce contrat ne possède pas de mandataire</span>
	</div>
	<?php endif; ?>
	<div>
		<span>Première mise en marché :</span>
		<span><?php echo ($vrac->premiere_mise_en_marche)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<span>Contrat entre producteurs 5% ou OTNA :</span>
		<span><?php echo ($vrac->production_otna)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<span>Apport contractuel à une union :</span>
		<span><?php echo ($vrac->apport_union)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<span>Contrat interne entre deux filiales :</span>
		<span><?php echo ($vrac->cession_interne)? 'Oui' : 'Non'; ?></span>
	</div>
	<h2>Marché</h2>
	<div>
		<span>Type de transaction :</span>
		<span><?php echo $configurationVrac->formatTypesTransactionLibelle(array($vrac->type_transaction)); ?></span>
	</div>
	<div>
		<span>Produit :</span>
		<span><?php echo ($vrac->produit)? $vrac->getLibelleProduit() : null; ?></span>
	</div>
	<div>
		<span>Labels :</span>
		<span><?php echo $configurationVrac->formatLabelsLibelle($vrac->labels->getRawValue()->toArray()) ?></span>
	</div>
	<div>
		<span>Mentions :</span>
		<span><?php echo $configurationVrac->formatMentionsLibelle($vrac->mentions->getRawValue()->toArray()) ?></span>
	</div>
	<div>
		<span>Prix :</span>
		<span><?php echo $vrac->prix_unitaire ?> €/HL</span>
	</div>
	<div>
		<span>Volume :</span>
		<span><?php echo $vrac->volume_propose ?> HL</span>
	</div>
	<div>
		<span>Prix total :</span>
		<span><?php echo $vrac->prix_total ?> €</span>
	</div>
	<h2>Conditions</h2>
	<div>
		<span>Présence d'une annexe :</span>
		<span><?php echo ($vrac->annexe)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<span>Présence d'un contrat pluriannuel :</span>
		<span><?php echo ($vrac->contrat_pluriannuel)? 'Oui' : 'Non'; ?></span>
	</div>
	<?php if ($vrac->contrat_pluriannuel): ?>
	<div>
		<span>Référence contrat pluriannuel: :</span>
		<span><?php echo $vrac->reference_contrat_pluriannuel ?></span>
	</div>
	<?php endif; ?>
	<div>
		<span>Part CVO payé par l'acheteur :</span>
		<span><?php echo $vrac->part_cvo ?></span>
	</div>
	<div>
		<span>Conditions générales de paiement :</span>
		<span><?php echo $configurationVrac->formatConditionsPaiementLibelle($vrac->conditions_paiement->getRawValue()->toArray()) ?></span>
	</div>
	<div>
		<span>Delai de paiement :</span>
		<span><?php echo $configurationVrac->formatDelaisPaiementLibelle(array($vrac->delai_paiement)) ?></span>
	</div>
	<div>
		<span>Echeancier de paiement :</span>
		<span><?php echo ($vrac->echeancier_paiement)? 'Oui' : 'Non'; ?></span>
	</div>
	<?php if ($vrac->echeancier_paiement): ?>
	<div>
		<?php foreach ($vrac->paiements as $paiement): ?>
		<div>
			<div>
				<span>Date :</span>
				<span><?php echo $paiement->date ?></span>
			</div>
			<div>
				<span>Montant :</span>
				<span><?php echo $paiement->montant ?> €</span>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<div>
		<span>Clause de reserve de propriété :</span>
		<span><?php echo ($vrac->clause_reserve_retiraison)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<span>Le vin sera livré :</span>
		<span><?php echo ($vrac->vin_livre)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<span>Date de début de retiraison :</span>
		<span><?php echo $vrac->date_debut_retiraison ?></span>
	</div>
	<div>
		<span>Date limite de retiraison :</span>
		<span><?php echo $vrac->date_limite_retiraison ?></span>
	</div>
	<div>
		<span>Calendrier de retiraison :</span>
		<span><?php echo ($vrac->calendrier_retiraison)? 'Oui' : 'Non'; ?></span>
	</div>
	<?php if ($vrac->calendrier_retiraison): ?>
	<div>
		<?php foreach ($vrac->retiraisons as $retiraison): ?>
		<div>
			<div>
				<span>Lot / Cuve :</span>
				<span><?php echo $retiraison->lot_cuve ?></span>
			</div>
			<div>
				<span>Date de retiraison :</span>
				<span><?php echo $retiraison->date_retiraison ?></span>
			</div>
			<div>
				<span>Volume retiré :</span>
				<span><?php echo $retiraison->volume_retire ?></span>
			</div>
			<div>
				<span>Montant du montant :</span>
				<span><?php echo $retiraison->montant_paiement ?> €</span>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<div>
		<span>Commentaire :</span>
		<span><?php echo $vrac->commentaires_conditions ?></span>
	</div>
	<?php if ($vrac->has_transaction): ?>
	<h2>Transaction</h2>
	<div>
		<span>Expédition export :</span>
		<span><?php echo ($vrac->export)? 'Oui' : 'Non'; ?></span>
	</div>
	<div>
		<?php foreach ($vrac->lots as $lot): ?>
		<div>
			<div>
				<span>Numéro du lot :</span>
				<span><?php echo $lot->numero ?></span>
			</div>
			<div>
				<span>Contenance de la cuve :</span>
				<span><?php echo $lot->contenance_cuve ?></span>
			</div>
			<div>
				<span>Millésime :</span>
				<span><?php echo $lot->millesime ?></span>
			</div>
			<div>
				<span>Pourcentage du millésime :</span>
				<span><?php echo $lot->pourcentage_annee ?></span>
			</div>
			<div>
				<span>Degré :</span>
				<span><?php echo $lot->degre ?></span>
			</div>
			<div>
				<span>Présence d'allergènes :</span>
				<span><?php echo ($lot->presence_allergenes)? 'oui' : 'non'; ?></span>
			</div>
			<div>
				<span>Volume :</span>
				<span><?php echo $lot->volume ?> HL</span>
			</div>
			<div>
				<span>Date de retiraison :</span>
				<span><?php echo $lot->date_retiraison ?></span>
			</div>
			<div>
				<span>Commentaire :</span>
				<span><?php echo $configurationVrac->formatCommentairesLotLibelle(array($lot->commentaires)) ?></span>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div>
		<span>Commentaire :</span>
		<span><?php echo $vrac->commentaires ?></span>
	</div>
	<?php endif; ?>
</div>