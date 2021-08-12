<ol>
	<li>
		<h3>Soussignés</h3>
        <ul>
			<li>
				<span>Vendeur :</span>
				<span>
					<?php if($vrac->vendeur->nom && !$vrac->vendeur->raison_sociale): ?>
						<?php echo $vrac->vendeur->nom ?>
					<?php endif; ?>
					<?php if($vrac->vendeur->raison_sociale): ?>
						<?php echo $vrac->vendeur->raison_sociale; ?>
					<?php endif; ?>
					<?php echo ($vrac->vendeur->famille)? ' - '.ucfirst(($vrac->vendeur->famille)) : ''; ?>
					<?php echo ($vrac->vendeur->sous_famille)? ' '.ucfirst(($vrac->vendeur->sous_famille)) : ''; ?>
					<?php echo ($vrac->vendeur_tva)? ' (Assujetti à la TVA)' : ''; ?>
				</span>
			</li>
			<li>
				<span>Acheteur :</span>
				<span>
					<?php if($vrac->acheteur->nom && !$vrac->acheteur->raison_sociale): ?>
						<?php echo $vrac->acheteur->nom ?>
					<?php endif; ?>
					<?php if($vrac->acheteur->raison_sociale): ?>
						<?php echo $vrac->acheteur->raison_sociale; ?>
					<?php endif; ?>
					<?php echo ($vrac->acheteur->famille)? ' - '.ucfirst(($vrac->acheteur->famille)) : ''; ?>
					<?php echo ($vrac->acheteur->sous_famille)? ' '.ucfirst(($vrac->acheteur->sous_famille)) : ''; ?>
					<?php echo ($vrac->acheteur_tva)? ' (Assujetti à la TVA)' : ''; ?>
				</span>
			</li>
			<?php if($vrac->mandataire_exist): ?>
			<li>
				<span>Courtier :</span>
				<span>
					<?php if($vrac->mandataire->nom && !$vrac->mandataire->raison_sociale): ?>
						<?php echo $vrac->mandataire->nom ?>
					<?php endif; ?>
					<?php if($vrac->mandataire->raison_sociale): ?>
						<?php echo $vrac->mandataire->raison_sociale; ?>
					<?php endif; ?>
				</span>
			</li>
			<?php endif; ?>
    	</ul>
    	<?php if($editer_etape): ?>
    	<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'soussigne', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
   		<?php endif; ?>
    </li>
    <li>
	    <h3>Produit / Qualité / Origine</h3>
        <ul>
			<li>
				<span>Type de produit :</span>
				<span><?php echo $configurationVrac->formatTypesTransactionLibelle(array($vrac->type_transaction)); ?></span>
			</li>
        	<li>
				<span><?php if($vrac->isConditionneIvse()): ?>Dénomination<?php else: ?>Appellation<?php endif; ?> concernée :</span>
				<span><?php echo ($vrac->produit)? $vrac->getLibelleProduit() : null; ?></span>
			</li>
			<li>
				<span>Millésime :</span>
				<span><?php echo ($vrac->millesime)? $vrac->millesime : 'Non millésimé'; ?></span>
			</li>
			<li>
				<span>Label(s) :</span>
				<span><?php echo ($vrac->labels)? $configurationVrac->formatLabelsLibelle(array($vrac->labels)) : $configurationVrac->formatLabelsLibelle($vrac->getLibellesLabels()) ?></span>				
			</li>
			<li>
				<span>Mention(s) :</span>
				<span><?php echo $configurationVrac->formatMentionsLibelle($vrac->getLibellesMentions()) ?></span>				
			</li>
			<?php $mentions = $vrac->getMentions()->getRawValue()->toArray() ?>
			<?php if (in_array('chdo', $mentions)): ?>
			<li>
				<span>Rappel :</span>
				<span>Le vendeur autorise expressément l'Acheteur à utiliser son nom d'exploitation. Ce dernier devra être indiqué sur la facture et le document d'accompagnement. L'Acheteur devra respecter les exigences du décret n° 2012-655 du 4 mai 2012.</span>
			</li>
			<?php endif; ?>
			<?php if (in_array('marque', $mentions)): ?>
			<li>
				<span>Rappel :</span>
				<span>Le vendeur autorise expressément l'Acheteur à utiliser sa marque.</span>
			</li>
			<?php endif; ?>
		</ul>
    	<?php if($editer_etape): ?>
    	<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'produit', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
   		<?php endif; ?>
	</li>
    <li>
	    <h3>Type de contrat</h3>
        <ul>
			<li>
				<span>Type de contrat :</span>
				<span><?php if ($vrac->contrat_pluriannuel): ?>Adossé à un contrat pluriannuel<?php if ($vrac->reference_contrat_pluriannuel): ?> (<?php echo $vrac->reference_contrat_pluriannuel ?>)<?php endif ?><?php else: ?>Ponctuel<?php endif; ?></span>
			</li>
		</ul>
    	<?php if($editer_etape): ?>
    	<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
   		<?php endif; ?>
	</li>
    <li>
	    <h3>Spécificités du contrat</h3>
        <ul>
			<li>
				<span>Condition particulière :</span>
				<span><?php echo $configurationVrac->formatCasParticulierLibelle(array($vrac->cas_particulier)); ?></span>
			</li>
			<li>
				<span>Expédition export :</span>
				<span><?php echo ($vrac->export)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php if(!$vrac->isConditionneIr()): ?>
			<li>
				<span>Première mise en marché :</span>
				<span><?php echo ($vrac->premiere_mise_en_marche)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php endif; ?>
			<?php if($vrac->isConditionneCivp()): ?>
			<li>
				<span>Entre bailleur et métayer :</span>
				<span><?php echo ($vrac->bailleur_metayer)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php endif; ?>
			<?php if ($vrac->annexe): ?>
			<li>
				<span>Présence d'une annexe :</span>
				<span>Oui</span>
			</li>
			<?php endif; ?>
		</ul>
    	<?php if($editer_etape): ?>
    	<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
   		<?php endif; ?>
	</li>
	<li>
	    <h3>Volume / Prix</h3>
        <ul>
			<li>
				<span>Volume :</span>
				<span><?php echo $vrac->volume_propose ?><?php if($vrac->type_transaction == 'raisin'): ?> Kg<?php else: ?> HL<?php endif; ?></span>
			</li>
			<li>
				<span>Prix unitaire net :</span>
				<span><?php echo $vrac->prix_unitaire ?> <?php if($vrac->type_transaction == 'raisin'): ?>€ HT / Kg<?php else: ?>€ HT / HL hors cotisations<?php endif;?></span>
			</li>
			<?php if ($vrac->type_transaction == 'vrac'): ?>
			<li>
				<span>Cotisation interprofessionnelle :</span>
				<span><?php echo $vrac->getCvoUnitaire() ?> € HT / HL</span>
			</li>
			<?php if ($vrac->has_cotisation_cvo && $vrac->part_cvo > 0): ?>
			<li>
				<span>Prix total unitaire :</span>
				<span><?php echo $vrac->getTotalUnitaire() ?> € HT / HL</span>
			</li>
			<li>
				<span>Prix total :</span>
				<span><?php echo round($vrac->volume_propose * $vrac->getTotalUnitaire(),2) ?> € HT</span>
			</li>
			<?php else: ?>
			<li>
				<span>Prix total :</span>
				<span><?php echo round($vrac->volume_propose * $vrac->prix_unitaire,2) ?> € HT</span>
			</li>
			<?php endif; ?>
			<?php endif; ?>
			<li>
				<span>Type de prix :</span>
				<span><?php echo $configurationVrac->formatTypesPrixLibelle(array($vrac->type_prix)) ?></span>
			</li>
			<?php if ($vrac->determination_prix_date): ?>
			<li>
				<span>Date de détermination du prix :</span>
				<span><?php echo Date::francizeDate($vrac->determination_prix_date) ?></span>
			</li>
			<?php endif; ?>
			<?php if ($vrac->determination_prix): ?>
			<li>
				<span>Mode de détermination du prix :</span>
				<span><?php echo $vrac->determination_prix ?></span>
			</li>
			<?php endif; ?>
			<?php if ($vrac->conditions_paiement): ?>
				<li>
					<span>Paiement :</span>
					<span><?php echo $configurationVrac->formatConditionsPaiementLibelle(array($vrac->conditions_paiement)); ?></span>
				</li>
				<?php if ($vrac->conditions_paiement == ConfigurationVrac::CONDITION_PAIEMENT_CADRE_REGLEMENTAIRE && $vrac->hasAcompteInfo()): ?>
					<li>
						<span>Rappel:</span>
						<span>Acompte obligatoire de 15% dans les 10 jours suivants la signature du contrat</span>
					</li>
				<?php endif; ?>
				<?php if ($vrac->conditions_paiement == ConfigurationVrac::CONDITION_PAIEMENT_ECHEANCIER): ?>
				<li>
					<span>Echéancier :</span>
					<span>
					<table id="table_paiements" style="display: inline;">
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
								<td><?php echo $paiement->montant ?> €</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</span>
				</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if(!is_null($vrac->delai_paiement)): ?>
			<li>
				<span>Delai de paiement :</span>
				<span><?php echo $configurationVrac->formatDelaisPaiementLibelle(array(str_replace('autre', $vrac->delai_paiement_autre, $vrac->delai_paiement))) ?></span>
			</li>
			<?php endif; ?>
			
		</ul>
		<?php if($editer_etape): ?>
		<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
		<?php endif; ?>
    </li>
    <li<?php if (!$vrac->has_transaction): ?> style="margin: 0;"<?php endif; ?>>
		<h3>Mode et date de retiraison / livraison</h3>
        <ul>
			<?php if (!$vrac->isConditionneIvse()): ?>
			<li>
				<span>Le vin sera :</span>
				<span><?php $statut = VracClient::getInstance()->getStatutsVin(); echo $statut[$vrac->vin_livre] ?></span>
			</li>
			<?php endif; ?>
			<?php if($vrac->type_retiraison): ?>
			<li>
				<span>Type de retiraison:</span>
				<span><?php echo $configurationVrac->formatTypesRetiraisonLibelle(array($vrac->type_retiraison)) ?></span>
			</li>
			<?php endif; ?>
			<?php if($vrac->date_debut_retiraison): ?>
			<li>
				<span>Date de début de retiraison:</span>
				<span><?php echo Date::francizeDate($vrac->date_debut_retiraison) ?></span>
			</li>
			<?php endif; ?>
			<li>
				<span>Date limite de retiraison:</span>
				<span><?php echo Date::francizeDate($vrac->date_limite_retiraison) ?></span>
			</li>
			<?php if(!is_null($vrac->clause_reserve_retiraison)): ?>
			<li>
				<span>Clause de reserve de propriété:</span>
				<span><?php echo ($vrac->clause_reserve_retiraison)? 'Oui' : 'Non'; ?></span>
			</li>
			<?php endif; ?>
			<?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $vrac->exist('observations') && !is_null($vrac->commentaires)): ?>
			<li>
				<span>Commentaires BO:</span>
				<span><?php echo $vrac->commentaires ?></span>
			</li>
			<?php endif; ?>
			<?php if($vrac->exist('observations') && $vrac->observations): ?>
			<li>
				<span>Observations:</span>
				<span><?php echo $vrac->observations ?></span>
			</li>
			<?php endif; ?>
		</ul>
		<?php if($editer_etape): ?>
			<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'marche', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
		<?php endif; ?>
    </li>
	<?php if ($vrac->has_transaction): ?>
    <li id="recap_transaction" style="margin: 0;">
		<h3>Transaction</h3>
        <ul>
			<li class="lots">
				<?php foreach ($vrac->lots as $lot): ?>
				<div class="lot">

					<p><span>Numéro du lot :</span> <?php echo $lot->numero ?></p>
					
					<?php if($lot->cuves): ?>
					<div class="cuves">
						<p><span>Détail :</span></p>
						<table>
							<thead>
								<tr>
									<th>Numéro(s) des cuves</th>
									<th>Volume</th>
									<th>Date</th>
				            	</tr>
				            </thead>
				            <tbody>
								<?php foreach ($lot->cuves as $cuve): ?>
								<tr>
									<td><?php echo $cuve->numero ?></td>
									<td><?php echo $cuve->volume ?>&nbsp;hl</td>
									<td><?php echo Date::francizeDate($cuve->date) ?></td>
								</tr>
								<?php endforeach; ?>
				            </tbody>
				        </table>
					</div>
					<?php endif; ?>

					<?php if($lot->millesimes[0]->annee): ?>
					<div class="millesimes">
						<p><span>Assemblage de millésimes :</span></p>

						<table>
							<thead>
								<tr>
									<th>Millésime</th>
									<th>Pourcentage (%)</th>
				            	</tr>
				            </thead>
				            <tbody>
								<?php foreach ($lot->millesimes as $millesime): ?>
								<tr>
									<td><?php echo $millesime->annee ?></td>
									<td><?php echo $millesime->pourcentage ?></td>
								</tr>
								<?php endforeach; ?>
				            </tbody>
				        </table>
					</div>
					<?php endif; ?>
					
					<?php if (!is_null($lot->metayage)): ?>
					<p><span>Métayage :</span> <?php echo ($lot->metayage)? 'Oui' : 'Non'; ?></p>
						<?php if($lot->bailleur): ?>	
						<p><span>Nom du bailleur et volumes :</span> <?php echo $lot->bailleur ?></p>	
						<?php endif; ?>
					<?php endif; ?>
					
					<?php if ($lot->degre): ?>
					<p><span>Degré :</span> <?php echo $lot->degre ?></p>
					<?php endif; ?>
					
					<?php if (!is_null($lot->presence_allergenes)): ?>
					<p><span>Présence d'allergènes :</span> <?php echo ($lot->presence_allergenes)? 'Oui' : 'Non'; ?></p>
					<?php endif; ?>
				
				</div>
				<?php endforeach; ?>
			</li>
		</ul>
		<?php if($editer_etape): ?>
			<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
		<?php endif; ?>
	</li>
    <?php endif; ?>
    <?php if(!isset($hasClauses) || $hasClauses): ?>
    <?php if ($vrac->clauses_complementaires || $vrac->autres_conditions): ?>
	<li>
	    <h3>Clauses</h3>
        <ul>
        	<?php if ($vrac->clauses_complementaires): ?>
			<li>
				<span>Complémentaire(s) :</span>
				<span><?php echo str_replace(array('_', ','), array(' ', ', '), $vrac->clauses_complementaires) ?></span>
			</li>
			<?php endif ?>
			<?php if ($vrac->autres_conditions): ?>
			<li>
				<span>Autres conditions :</span>
				<span><?php echo $vrac->autres_conditions ?></span>
			</li>
			<?php endif ?>
			<?php if (count($vrac->_attachments) > 0): ?>
			<li>
				<span>Annexe PDF :</span>
				<span><a href="<?php echo url_for('vrac_annexe', $vrac) ?>"><?php echo $vrac->annexe_file ?></a></span>
			</li>
			<?php endif; ?>
		</ul>
    		<?php if($editer_etape): ?>
    			<p><a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => 'clause', 'etablissement' => $etablissement)) ?>" class="modifier">modifier</a></p>
    		<?php endif; ?>
	</li>
	<?php endif; ?>
	<?php endif; ?>
    <?php if ($vrac->hasEnlevements() && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    <li id="recap_enlevements" style="margin-top: 15px;">
		<h3>Enlevements</h3>
		<ul>
			<?php 
				foreach ($vrac->enlevements as $drm => $enlevement): 
				if ($d = DRMClient::getInstance()->find($drm)):
			?>
			<li>
				<span><a href="<?php echo url_for('drm_visualisation', $d); ?>"><?php echo $drm ?></a></span>
				<span><?php echo $enlevement->volume ?> <?php if($vrac->type_transaction != 'raisin'): ?>hl<?php else: ?>kg<?php endif;?></span>
			</li>
			<?php endif; endforeach; ?>
		</ul>
	</li>
    <?php endif; ?>
</ol>
