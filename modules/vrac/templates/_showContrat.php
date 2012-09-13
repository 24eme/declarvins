<ol>
	<li>
		<h3>Soussignés</h3>
        <ul>
			<li>
				<span>Vendeur :</span>
				<span>
					<?php if($vrac->vendeur->nom): ?>
						<?php echo $vrac->vendeur->nom ?>
					<?php else: ?>
						<?php echo $vrac->vendeur->raison_sociale ?>
					<?php endif; ?>
				</span>
			</li>
			<li>
				<span>Assujetti à la TVA :</span>
				<span><?php echo ($vrac->vendeur_tva)? 'oui' : 'non'; ?></span>
			</li>
			<li>
				<span>Acheteur :</span>
				<span>
					<?php if($vrac->acheteur->nom): ?>
						<?php echo $vrac->acheteur->nom ?>
					<?php else: ?>
						<?php echo $vrac->acheteur->raison_sociale ?>
					<?php endif; ?>
				</span>
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
    	<?php if($editer_etape): ?>
    	<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
   		<?php endif; ?>
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
			<?php if ($vrac->has_cotisation_cvo && $vrac->part_cvo > 0): ?>
			<li>
				<span>Cotisation interprofessionnelle :</span>
				<span><?php echo $vrac->getCvoUnitaire() ?> €/HL</span>
			</li>
			<li>
				<span>Prix total unitaire :</span>
				<span><?php echo $vrac->getTotalUnitaire() ?> €/HL</span>
			</li>
			<?php endif; ?>
		</ul>
		<?php if($editer_etape): ?>
		<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
		<?php endif; ?>
    </li>
    <li>
		<h3>Conditions</h3>
        <ul>
        	<?php if(!is_null($vrac->annexe)): ?>
			<li>
				<span>Présence d'une annexe :</span>
				<span><?php echo ($vrac->annexe)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php endif; ?>
			<li>
				<span>Présence d'un contrat pluriannuel :</span>
				<span><?php echo ($vrac->contrat_pluriannuel)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php if (!is_null($vrac->reference_contrat_pluriannuel)): ?>
			<li>
				<span>Référence contrat pluriannuel :</span>
				<span><?php echo $vrac->reference_contrat_pluriannuel ?></span>
			</li>
			<?php endif; ?>
			<li>
				<span>Conditions générales de paiement :</span>
				<span><?php echo $configurationVrac->formatConditionsPaiementLibelle(array($vrac->conditions_paiement)); ?></span>
			</li>
			<?php if(!is_null($vrac->delai_paiement)): ?>
			<li>
				<span>Delai de paiement :</span>
				<span><?php echo $configurationVrac->formatDelaisPaiementLibelle(array($vrac->delai_paiement)) ?></span>
			</li>
			<?php endif; ?>
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
			<?php if(!is_null($vrac->clause_reserve_retiraison)): ?>
			<li>
				<span>Clause de reserve de propriété :</span>
				<span><?php echo ($vrac->clause_reserve_retiraison)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php endif; ?>
			<li>
				<span>Le vin sera :</span>
				<span><?php echo $vrac->vin_livre ?></span>
			</li>
			<li>
				<span>Date limite de retiraison :</span>
				<span><?php echo $vrac->date_limite_retiraison ?></span>
			</li>
		</ul>
		<?php if($editer_etape): ?>
			<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
		<?php endif; ?>
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
						<span>Cuve :</span>
						<span><?php echo $lot->cuve ?></span>
					</div>
					<?php foreach ($lot->millesimes as $millesime): ?>
					<div>
						<div>
							<span>Année :</span>
							<span><?php echo $millesime->annee ?></span>
						</div>
						<div>
							<span>Pourcentage :</span>
							<span><?php echo $millesime->pourcentage ?> %</span>
						</div>
					</div>
					<?php endforeach; ?>
					<div>
						<span>Degré :</span>
						<span><?php echo $lot->degre ?></span>
					</div>
					<div>
						<span>Présence d'allergènes :</span>
						<span><?php echo ($lot->presence_allergenes)? 'Oui' : 'Non'; ?></span>
					</div>
					<div>
						<span>Volume :</span>
						<span><?php echo $lot->volume ?> HL</span>
					</div>
					<div>
						<span>Date de retiraison :</span>
						<span><?php echo $lot->date_retiraison ?></span>
					</div>
				</div>
				<?php endforeach; ?>
			</li>
		</ul>
		<?php if($editer_etape): ?>
			<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
		<?php endif; ?>
	</li>
    <?php endif; ?>
</ol>
