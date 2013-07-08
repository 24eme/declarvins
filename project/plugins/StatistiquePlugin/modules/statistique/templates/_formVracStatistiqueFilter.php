<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques_vrac') ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	
	<fieldset id="filtre_vendeur_identifiant" class="champs_dynamiques">
		<legend>Filtrer par identifiant du vendeur</legend>
		<div id="filtre_vendeur_identifiant_items">
			<?php foreach ($form['vendeur_identifiant'] as $subForm): ?>
				<?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Vendeur : ')) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_vendeur_identifiant #filtre_vendeur_identifiant_items" data-template="#template_form_vendeur_identifiant_item" href="#"><span>Ajouter</span></a>
	</fieldset>
	
	<fieldset id="filtre_identifiant">
		<legend>Filtrer par identifiant</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['_id']->renderError() ?></span>
			<?php echo $form['_id']->renderLabel() ?><?php echo $form['_id']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_produit" class="champs_dynamiques">
		<legend>Filtrer par produits</legend>
		<div id="filtre_produits_items">
			<?php foreach ($form['produit'] as $formProduit): ?>
				<?php include_partial('form_produits_item', array('form' => $formProduit)) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_produits #filtre_produits_items" data-template="#template_form_produits_item" href="#"><span>Ajouter</span></a>
	</fieldset>
	
	<fieldset id="filtre_acheteur_identifiant" class="champs_dynamiques">
		<legend>Filtrer par identifiant de l'acheteur</legend>
		<div id="filtre_acheteur_identifiant_items">
			<?php foreach ($form['acheteur_identifiant'] as $subForm): ?>
				<?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Acheteur : ')) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_acheteur_identifiant #filtre_acheteur_identifiant_items" data-template="#template_form_acheteur_identifiant_item" href="#"><span>Ajouter</span></a>
	</fieldset>
	
	<fieldset id="filtre_cas_particuliers">
		<legend>Filtrer par conditions particulières</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['cas_particulier']->renderError() ?></span>
			<?php echo $form['cas_particulier']->renderLabel() ?><?php echo $form['cas_particulier']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_millesime">
		<legend>Filtrer par millesime</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['millesime']->renderError() ?></span>
			<?php echo $form['millesime']->renderLabel() ?><?php echo $form['millesime']->render() ?>
			<br /><i style="font-size: 10px;">Multiple, utilisez le séparateur ;</i>
		</div>
	</fieldset>
	
	<fieldset id="filtre_mandataire_identifiant" class="champs_dynamiques">
		<legend>Filtrer par identifiant du mandataire</legend>
		<div id="filtre_mandataire_identifiant_items">
			<?php foreach ($form['mandataire_identifiant'] as $subForm): ?>
				<?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Courtier : ')) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_mandataire_identifiant #filtre_mandataire_identifiant_items" data-template="#template_form_mandataire_identifiant_item" href="#"><span>Ajouter</span></a>
	</fieldset>
	
	<fieldset id="filtre_type_transaction">
		<legend>Filtrer par type de transaction</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['type_transaction']->renderError() ?></span>
			<?php echo $form['type_transaction']->renderLabel() ?><?php echo $form['type_transaction']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_label">
		<legend>Filtrer par labels</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['labels']->renderError() ?></span>
			<?php echo $form['labels']->renderLabel() ?><?php echo $form['labels']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_code_postal_vendeur">
		<legend>Filtrer par code postal du vendeur</legend>
		<div class="ligne_form code_postal">
			<span class="error"><?php echo $form['vendeur.code_postal']->renderError() ?></span>
			<?php echo $form['vendeur.code_postal']->renderLabel() ?><?php echo $form['vendeur.code_postal']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_export">
		<legend>Filtrer par export</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['export']->renderError() ?></span>
			<?php echo $form['export']->renderLabel() ?><?php echo $form['export']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_mention">
		<legend>Filter par mentions</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['mentions']->renderError() ?></span>
			<?php echo $form['mentions']->renderLabel() ?><?php echo $form['mentions']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_code_postal_acheteur">
		<legend>Filtrer par code postal de l'acheteur</legend>
		<div class="ligne_form code_postal">
			<span class="error"><?php echo $form['acheteur.code_postal']->renderError() ?></span>
			<?php echo $form['acheteur.code_postal']->renderLabel() ?><?php echo $form['acheteur.code_postal']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_annexe">
		<legend>Filtrer par annexe</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['annexe']->renderError() ?></span>
			<?php echo $form['annexe']->renderLabel() ?><?php echo $form['annexe']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_famille_vendeur">
		<legend>Filtrer par famille de vendeur</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['vendeur.famille']->renderError() ?></span>
			<?php echo $form['vendeur.famille']->renderLabel() ?><?php echo $form['vendeur.famille']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_type_prix">
		<legend>Filtrer par type de prix</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['type_prix']->renderError() ?></span>
			<?php echo $form['type_prix']->renderLabel() ?><?php echo $form['type_prix']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_sous_famille_vendeur">
		<legend>Filtrer par sous famille de vendeur</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['vendeur.sous_famille']->renderError() ?></span>
			<?php echo $form['vendeur.sous_famille']->renderLabel() ?><?php echo $form['vendeur.sous_famille']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_date_limite_retiraison">
		<legend>Filter par date limite de retiraison</legend>
		<div class="ligne_form select_date">
			<span class="error"><?php echo $form['date_limite_retiraison']->renderError() ?></span>
			<?php echo $form['date_limite_retiraison']->renderLabel() ?><?php echo $form['date_limite_retiraison']->render() ?>
		</div>
		
		<div class="calendriers">
			<div class="calendrier_item calendrier_debut">
				<div class="instruction">Choisir la date de début</div>

				<div class="date_picker"></div>
			</div>
			<div class="calendrier_item calendrier_fin">
				<div class="instruction">Choisir la date de fin</div>

				<div class="date_picker"></div>
			</div>
		</div>
	</fieldset>
	
	<fieldset id="filtre_famille_acheteur">
		<legend>Filter par famille d'acheteur</legend>

		<div class="ligne_form">
			<span class="error"><?php echo $form['acheteur.famille']->renderError() ?></span>
			<?php echo $form['acheteur.famille']->renderLabel() ?><?php echo $form['acheteur.famille']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_date_saisie">
		<legend>Filtrer par date de saisie</legend>

		<div class="ligne_form select_date">
			<span class="error"><?php echo $form['valide.date_saisie']->renderError() ?></span>
			<?php echo $form['valide.date_saisie']->renderLabel() ?><?php echo $form['valide.date_saisie']->render() ?>
		</div>
		
		<div class="calendriers">
			<div class="calendrier_item calendrier_debut">
				<div class="instruction">Choisir la date de début</div>

				<div class="date_picker"></div>
			</div>
			<div class="calendrier_item calendrier_fin">
				<div class="instruction">Choisir la date de fin</div>

				<div class="date_picker"></div>
			</div>
		</div>
	</fieldset>
	
	<fieldset id="filtre_sous_famille_acheteur">
		<legend>Filter par sous famille d'acheteur</legend>

		<div class="ligne_form">
			<span class="error"><?php echo $form['acheteur.sous_famille']->renderError() ?></span>
			<?php echo $form['acheteur.sous_famille']->renderLabel() ?><?php echo $form['acheteur.sous_famille']->render() ?>
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