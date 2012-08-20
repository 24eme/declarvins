<ol>
	<li>
		<h3>Soussignés</h3>
        <ul>
			<li>
				<span>Vendeur :</span>
				<span><?php echo $vrac->vendeur->nom ?></span>
			</li>
			<li>
				<span>Assujetti à la TVA :</span>
				<span><?php echo ($vrac->vendeur_tva)? 'oui' : 'non'; ?></span>
			</li>
			<li>
				<span>Acheteur :</span>
				<span><?php echo $vrac->acheteur->nom ?></span>
			</li>
			<li>
				<span>Assujetti à la TVA :</span>
				<span><?php echo ($vrac->acheteur_tva)? 'oui' : 'non'; ?></span>
			</li>
			<?php if($vrac->mandataire_exist): ?>
			<li>
				<span>Mandataire :</span>
				<span><?php echo $vrac->mandataire->nom ?></span>
			</li>
			<?php else: ?>
			<li>
				<span>Ce contrat ne possède pas de mandataire</span>
			</li>
			<?php endif; ?>
			<li>
				<span>Première mise en marché :</span>
				<span><?php echo ($vrac->premiere_mise_en_marche)? 'Oui' : 'Non'; ?></span>
			</li>
			<li>
				<span>Cas particulier :</span>
				<span><?php echo $configurationVrac->formatCasParticulierLibelle(array($vrac->cas_particulier)); ?></span>
			</li>
    	</ul>
    	<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
    </li>
	<li>
	    <h3>Marché</h3>
        <ul>
			<li>
				<span>Type de transaction :</span>
				<span><?php echo $configurationVrac->formatTypesTransactionLibelle(array($vrac->type_transaction)); ?></span>
			</li>
			<li>
				<span>Produit :</span>
				<span><?php echo ($vrac->produit)? $vrac->getLibelleProduit() : null; ?></span>
			</li>
			<li>
				<span>Labels :</span>
				<span><?php echo $configurationVrac->formatLabelsLibelle($vrac->labels->getRawValue()->toArray()) ?></span>
			</li>
			<li>
				<span>Mentions :</span>
				<span><?php echo $configurationVrac->formatMentionsLibelle($vrac->mentions->getRawValue()->toArray()) ?></span>
			</li>
			<li>
				<span>Prix :</span>
				<span><?php echo $vrac->prix_unitaire ?> €/HL</span>
			</li>
			<li>
				<span>Volume :</span>
				<span><?php echo $vrac->volume_propose ?> HL</span>
			</li>
			<li>
				<span>Prix total :</span>
				<span><?php echo $vrac->prix_total ?> €</span>
			</li>
		</ul>
		<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
    </li>
    <li>
		<h3>Conditions</h3>
        <ul>
			<li>
				<span>Présence d'une annexe :</span>
				<span><?php echo ($vrac->annexe)? 'Oui' : 'Non'; ?></span>
			</li>
			<li>
				<span>Présence d'un contrat pluriannuel :</span>
				<span><?php echo ($vrac->contrat_pluriannuel)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php if ($vrac->contrat_pluriannuel): ?>
			<li>
				<span>Référence contrat pluriannuel: :</span>
				<span><?php echo $vrac->reference_contrat_pluriannuel ?></span>
			</li>
			<?php endif; ?>
			<li>
				<span>Part CVO payé par l'acheteur :</span>
				<span><?php echo $vrac->part_cvo ?></span>
			</li>
			<li>
				<span>Conditions générales de paiement :</span>
				<span><?php echo $configurationVrac->formatConditionsPaiementLibelle($vrac->conditions_paiement->getRawValue()->toArray()) ?></span>
			</li>
			<li>
				<span>Delai de paiement :</span>
				<span><?php echo $configurationVrac->formatDelaisPaiementLibelle(array($vrac->delai_paiement)) ?></span>
			</li>
			<li>
				<span>Echeancier de paiement :</span>
				<span><?php echo ($vrac->echeancier_paiement)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php if ($vrac->echeancier_paiement): ?>
			<li>
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
			</li>
			<?php endif; ?>
			<li>
				<span>Clause de reserve de propriété :</span>
				<span><?php echo ($vrac->clause_reserve_retiraison)? 'Oui' : 'Non'; ?></span>
			</li>
			<li>
				<span>Le vin sera livré :</span>
				<span><?php echo ($vrac->vin_livre)? 'Oui' : 'Non'; ?></span>
			</li>
			<li>
				<span>Date de début de retiraison :</span>
				<span><?php echo $vrac->date_debut_retiraison ?></span>
			</li>
			<li>
				<span>Date limite de retiraison :</span>
				<span><?php echo $vrac->date_limite_retiraison ?></span>
			</li>
			<li>
				<span>Commentaire :</span>
				<span><?php echo $vrac->commentaires_conditions ?></span>
			</li>
		</ul>
		<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
    </li>
	<?php if ($vrac->has_transaction): ?>
    <li>
		<h3>Transaction</h3>
        <ul>
			<li>
				<span>Expédition export :</span>
				<span><?php echo ($vrac->export)? 'Oui' : 'Non'; ?></span>
			</li>
			<li>
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
			</li>
			<li>
				<span>Commentaire :</span>
				<span><?php echo $vrac->commentaires ?></span>
			</li>
		</ul>
		<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
	</li>
    <?php endif; ?>
</ol>
