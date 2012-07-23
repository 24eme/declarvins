<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
	<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>
	<form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<hr />
		<h2>Paiement</h2>
		<hr />
		<div>
			<div>
                <?php echo $form['annexe']->renderError() ?>
				<?php echo $form['annexe']->renderLabel() ?>
				<?php echo $form['annexe']->render() ?>
			</div>
			<div>
                <?php echo $form['type_prix']->renderError() ?>
				<?php echo $form['type_prix']->renderLabel() ?>
				<?php echo $form['type_prix']->render() ?>
			</div>
			<div>
                <?php echo $form['part_cvo']->renderError() ?>
				<?php echo $form['part_cvo']->renderLabel() ?>
                <?php echo $form['part_cvo']->render() ?>
			</div>
			<div>
                <?php echo $form['conditions_paiement']->renderError() ?>
				<?php echo $form['conditions_paiement']->renderLabel() ?>
				<?php echo $form['conditions_paiement']->render() ?>
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
                <table id="table_paiements">
                <?php foreach ($form['paiements'] as $formPaiement): ?>
                    <?php include_partial('form_paiements_item', array('form' => $formPaiement)) ?>
                <?php endforeach; ?>
                </table>
                <a class="btn_ajouter_ligne_template" data-container="#table_paiements" data-template="#template_form_paiements_item" href="#">Ajouter</a> 
			</div>
		</div>
		<hr />
		<h2>Livraison</h2>
		<hr />
		<div id="paiement">
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
                <?php echo $form['date_limite_retiraison']->renderError() ?>
				<?php echo $form['date_limite_retiraison']->renderLabel() ?>
				<?php echo $form['date_limite_retiraison']->render() ?>
			</div>
			<div>
                <?php echo $form['calendrier_retiraison']->renderError() ?>
				<?php echo $form['calendrier_retiraison']->renderLabel() ?>
				<?php echo $form['calendrier_retiraison']->render() ?>
			</div>
			<div> 
                <table id="table_retiraisons">
				<?php foreach ($form['retiraisons'] as $formRetiraison): ?>
                    <?php include_partial('form_retiraisons_item', array('form' => $formRetiraison)) ?>
				<?php endforeach; ?>
                </table>
                <a class="btn_ajouter_ligne_template" data-container="#table_retiraisons" data-template="#template_form_retiraisons_item" href="#">Ajouter</a>
			</div>
			<div>
                <?php echo $form['clause_reserve_retiraison']->renderError() ?>
				<?php echo $form['clause_reserve_retiraison']->renderLabel() ?>
				<?php echo $form['clause_reserve_retiraison']->render() ?>
			</div>
			<div>
                <?php echo $form['commentaires_conditions']->renderError() ?>
				<?php echo $form['commentaires_conditions']->renderLabel() ?>
                <?php echo $form['commentaires_conditions']->render() ?>
			</div>
		</div>
		<div class="ligne_form_btn">
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>

<?php include_partial('form_collection_template', array('partial' => 'form_retiraisons_item', 
                                                        'form' => $form->getFormTemplateRetiraisons())); ?>

<?php include_partial('form_collection_template', array('partial' => 'form_paiements_item', 
                                                        'form' => $form->getFormTemplatePaiements())); ?>
