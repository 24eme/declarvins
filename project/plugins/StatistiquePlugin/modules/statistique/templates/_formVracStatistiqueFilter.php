<form id="form_ajout" action="<?php echo url_for('statistiques_vrac') ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	

	<fieldset class="acteurs">
		<legend>Document</legend>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<span class="error"><?php echo $form['_id']->renderError() ?></span>
				<?php echo $form['_id']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['_id']->render() ?>
			</div>

			<div class="col_form">
				<span class="error"><?php echo $form['valide.statut']->renderError() ?></span>
				<?php echo $form['valide.statut']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['valide.statut']->render() ?>
			</div>

			<div class="col_form">
				<span class="error"><?php echo $form['type_transaction']->renderError() ?></span>
				<?php echo $form['type_transaction']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['type_transaction']->render() ?>
			</div>

			<div class="col_form">
				<span class="error"><?php echo $form['vous_etes']->renderError() ?></span>
				<?php echo $form['vous_etes']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['vous_etes']->render() ?>
			</div>
		</div>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<span class="error"><?php echo $form['valide.date_saisie']->renderError() ?></span>
				<?php echo $form['valide.date_saisie']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['valide.date_saisie']->render() ?>
			</div>

			<div class="col_form">
				<span class="error"><?php echo $form['valide.date_validation']->renderError() ?></span>
				<?php echo $form['valide.date_validation']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['valide.date_validation']->render() ?>
			</div>
		</div>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<span class="error"><?php echo $form['cas_particulier']->renderError() ?></span>
				<?php echo $form['cas_particulier']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['cas_particulier']->render() ?>
			</div>

			<div class="col_form">
				<div class="ligne_form">
					<span class="error"><?php echo $form['volume_propose']->renderError() ?></span>
					<?php echo $form['volume_propose']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['volume_propose']->render() ?>
				</div>
				<div class="ligne_form">
					<span class="error"><?php echo $form['prix_unitaire']->renderError() ?></span>
					<?php echo $form['prix_unitaire']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['prix_unitaire']->render() ?>
				</div>
				<div class="ligne_form">
					<span class="error"><?php echo $form['date_limite_retiraison']->renderError() ?></span>
					<?php echo $form['date_limite_retiraison']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['date_limite_retiraison']->render() ?>
				</div>
			</div>

			<div class="col_form">
				<span class="error"><?php echo $form['type_prix']->renderError() ?></span>
				<?php echo $form['type_prix']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['type_prix']->render() ?>
			</div>
		</div>
	</fieldset>

	<fieldset class="document">
		<legend>Acteurs</legend>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<div id="filtre_vendeur_identifiant_items">
					<?php foreach ($form['vendeur_identifiant'] as $subForm): ?>
						<?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Vendeur : ')) ?>
					<?php endforeach; ?>
				</div>
				<a class="btn_ajouter_ligne_template" data-container="#filtre_vendeur_identifiant_items" data-template="#template_form_vendeur_identifiant_item" href="#"><span>Ajouter</span></a>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['vendeur.famille']->renderError() ?></span>
				<?php echo $form['vendeur.famille']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['vendeur.famille']->render() ?>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['vendeur.sous_famille']->renderError() ?></span>
				<?php echo $form['vendeur.sous_famille']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['vendeur.sous_famille']->render() ?>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['vendeur.code_postal']->renderError() ?></span>
				<?php echo $form['vendeur.code_postal']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['vendeur.code_postal']->render() ?>
			</div>
		</div>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<div id="filtre_acheteur_identifiant_items">
					<?php foreach ($form['acheteur_identifiant'] as $subForm): ?>
						<?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Acheteur : ')) ?>
					<?php endforeach; ?>
				</div>
				<a class="btn_ajouter_ligne_template" data-container="#filtre_acheteur_identifiant_items" data-template="#template_form_acheteur_identifiant_item" href="#"><span>Ajouter</span></a>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['acheteur.famille']->renderError() ?></span>
				<?php echo $form['acheteur.famille']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['acheteur.famille']->render() ?>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['acheteur.sous_famille']->renderError() ?></span>
				<?php echo $form['acheteur.sous_famille']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['acheteur.sous_famille']->render() ?>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['acheteur.code_postal']->renderError() ?></span>
				<?php echo $form['acheteur.code_postal']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['acheteur.code_postal']->render() ?>
			</div>
		</div>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<div id="filtre_mandataire_identifiant_items">
					<?php foreach ($form['mandataire_identifiant'] as $subForm): ?>
						<?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Courtier : ')) ?>
					<?php endforeach; ?>
				</div>
				<a class="btn_ajouter_ligne_template" data-container="#filtre_mandataire_identifiant_items" data-template="#template_form_mandataire_identifiant_item" href="#"><span>Ajouter</span></a>
			</div>
		</div>
	</fieldset>

	<fieldset class="produit">
		<legend>Produit</legend>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<div id="filtre_produits_items">
					<?php foreach ($form['produit'] as $formProduit): ?>
						<?php include_partial('form_produits_item', array('form' => $formProduit)) ?>
					<?php endforeach; ?>
				</div>
				<a class="btn_ajouter_ligne_template" data-container="#filtre_produits_items" data-template="#template_form_produits_item" href="#"><span>Ajouter</span></a>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['millesime']->renderError() ?></span>
				<?php echo $form['millesime']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['millesime']->render() ?>
				<br /><i style="font-size: 10px;">Multiple, utilisez le séparateur ;</i>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['labels']->renderError() ?></span>
				<?php echo $form['labels']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['labels']->render() ?>
			</div>
			<div class="col_form">
				<span class="error"><?php echo $form['mentions']->renderError() ?></span>
				<?php echo $form['mentions']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['mentions']->render() ?>
			</div>
		</div>

		<div class="ligne_form clearfix">
			<div class="col_form">
				<span class="error"><?php echo $form['export']->renderError() ?></span>
				<?php echo $form['export']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['export']->render() ?>
			</div>

			<div class="col_form">
				<span class="error"><?php echo $form['annexe']->renderError() ?></span>
				<?php echo $form['annexe']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['annexe']->render() ?>
			</div>
		</div>
	</fieldset>
	
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>

<?php include_partial('form_collection_template', array('partial' => 'form_produits_item', 'form' => $form->getFormTemplateProduits())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_vendeur_identifiant_item', 'form' => $form->getFormTemplateVendeurIdentifiant())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_acheteur_identifiant_item', 'form' => $form->getFormTemplateAcheteurIdentifiant())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_mandataire_identifiant_item', 'form' => $form->getFormTemplateMandataireIdentifiant())); ?>