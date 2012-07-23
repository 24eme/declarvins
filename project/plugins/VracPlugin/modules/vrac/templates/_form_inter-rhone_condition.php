<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
	<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>
	<form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>

		<div id="prix">
			<div>
                <?php echo $form['prix_unitaire']->renderError() ?>
				<?php echo $form['prix_unitaire']->renderLabel() ?>
                <?php echo $form['prix_unitaire']->render() ?>
			</div>
			<div>
                <?php echo $form['type_prix']->renderError() ?>
				<?php echo $form['type_prix']->renderLabel() ?>
				<?php echo $form['type_prix']->render() ?>
			</div>
			<div>
                <?php echo $form['date_limite_retiraison']->renderError() ?>
				<?php echo $form['date_limite_retiraison']->renderLabel() ?>
				<?php echo $form['date_limite_retiraison']->render() ?>
			</div>
			<div>
                <?php echo $form['commentaires_conditions']->renderError() ?>
				<?php echo $form['commentaires_conditions']->renderLabel() ?>
                <?php echo $form['commentaires_conditions']->render() ?>
			</div>
		</div>

		<div id="paiement">
			<div>
                <?php echo $form['part_cvo']->renderError() ?>
				<?php echo $form['part_cvo']->renderLabel() ?>
                <?php echo $form['part_cvo']->render() ?>
			</div>
			<div>
                <?php echo $form['prix_total']->renderError() ?>
				<?php echo $form['prix_total']->renderLabel() ?>
				<?php echo $form['prix_total']->render() ?>
			</div>
			<div>
                <?php echo $form['conditions_paiement']->renderError() ?>
				<?php echo $form['conditions_paiement']->renderLabel() ?>
				<?php echo $form['conditions_paiement']->render() ?>
			</div>
			<div>
                <?php echo $form['vin_livre']->renderError() ?>
				<?php echo $form['vin_livre']->renderLabel() ?>
				<?php echo $form['vin_livre']->render() ?>
			</div>
			<div>
                <?php echo $form['date_debut_retiraison']->renderError() ?>
				<?php echo $form['date_debut_retiraison']->renderLabel() ?>
				<?php echo $form['date_debut_retiraison']->render() ?>
			</div>
			<div>
                <?php echo $form['calendrier_retiraison']->renderError() ?>
				<?php echo $form['calendrier_retiraison']->renderLabel() ?>
				<?php echo $form['calendrier_retiraison']->render() ?>
			</div>
			<div> 
				<?php foreach ($form['retiraisons'] as $formRetiraison): ?>
				<table>
					<tr>
						<td>
							<?php echo $formRetiraison['lot_cuve']->renderError() ?>
							<?php echo $formRetiraison['lot_cuve']->renderLabel() ?>
							<?php echo $formRetiraison['lot_cuve']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formRetiraison['date_retiraison']->renderError() ?>
							<?php echo $formRetiraison['date_retiraison']->renderLabel() ?>
							<?php echo $formRetiraison['date_retiraison']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formRetiraison['volume_retire']->renderError() ?>
							<?php echo $formRetiraison['volume_retire']->renderLabel() ?>
							<?php echo $formRetiraison['volume_retire']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formRetiraison['montant_paiement']->renderError() ?>
							<?php echo $formRetiraison['montant_paiement']->renderLabel() ?>
							<?php echo $formRetiraison['montant_paiement']->render() ?>
						</td>
					</tr>
				</table>
				<?php endforeach; ?>
			</div>
			<div>
                <?php echo $form['contrat_pluriannuel']->renderError() ?>
				<?php echo $form['contrat_pluriannuel']->renderLabel() ?>
				<?php echo $form['contrat_pluriannuel']->render() ?>
			</div>
			<div>
                <?php echo $form['reference_contrat_pluriannuel']->renderError() ?>
				<?php echo $form['reference_contrat_pluriannuel']->renderLabel() ?>
				<?php echo $form['reference_contrat_pluriannuel']->render() ?>
			</div>
			<div>
                <?php echo $form['delai_paiement']->renderError() ?>
				<?php echo $form['delai_paiement']->renderLabel() ?>
				<?php echo $form['delai_paiement']->render() ?>
			</div>
			<div>
                <?php echo $form['echeancier_paiement']->renderError() ?>
				<?php echo $form['echeancier_paiement']->renderLabel() ?>
				<?php echo $form['echeancier_paiement']->render() ?>
			</div>
			<div> 
				<?php foreach ($form['paiements'] as $key => $formPaiement): ?>
				<table>
					<tr>
						<td>
							<?php echo $formPaiement['date']->renderError() ?>
							<?php echo $formPaiement['date']->renderLabel() ?>
							<?php echo $formPaiement['date']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formPaiement['montant']->renderError() ?>
							<?php echo $formPaiement['montant']->renderLabel() ?>
							<?php echo $formPaiement['montant']->render() ?>
						</td>
					</tr>
				</table>
				<?php endforeach; ?>
			</div>
			<div>
                <?php echo $form['clause_reserve_retiraison']->renderError() ?>
				<?php echo $form['clause_reserve_retiraison']->renderLabel() ?>
				<?php echo $form['clause_reserve_retiraison']->render() ?>
			</div>
		</div>
		<div class="ligne_form_btn">
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>