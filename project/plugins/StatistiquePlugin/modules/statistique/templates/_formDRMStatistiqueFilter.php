<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques_drm') ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	
	<fieldset id="filtre_etablissements" class="champs_dynamiques">
		<legend>Filtrer par établissement</legend>
		<div id="filtre_etablissements_items">
			<?php foreach ($form['identifiant'] as $formEtablissement): ?>
				<?php include_partial('form_etablissements_item', array('form' => $formEtablissement)) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_etablissements #filtre_etablissements_items" data-template="#template_form_etablissements_item" href="#"><span>Ajouter</span></a>
	</fieldset>
	
	<fieldset id="filtre_identifiant">
		<legend>Filter par identifiant</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['_id']->renderError() ?></span>
			<?php echo $form['_id']->renderLabel() ?><?php echo $form['_id']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_produits" class="champs_dynamiques">
		<legend>Filtrer par produits</legend>
		<div id="filtre_produits_items">
			<?php foreach ($form['declaration'] as $formProduit): ?>
				<?php include_partial('form_produits_item', array('form' => $formProduit)) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_produits #filtre_produits_items" data-template="#template_form_produits_item" href="#"><span>Ajouter</span></a>
     </fieldset>
	
	<fieldset id="filtre_code_postal">
		<legend>Filtrer par code postal</legend>
		<div class="ligne_form code_postal">
			<span class="error"><?php echo $form['declarant.siege.code_postal']->renderError() ?></span>
			<?php echo $form['declarant.siege.code_postal']->renderLabel() ?><?php echo $form['declarant.siege.code_postal']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_identifiant_historique">
		<legend>Filtrer par identifiant historique</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['identifiant_drm_historique']->renderError() ?></span>
			<?php echo $form['identifiant_drm_historique']->renderLabel() ?><?php echo $form['identifiant_drm_historique']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_service_douane">
		<legend>Filtrer par service douane</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['declarant.service_douane']->renderError() ?></span>
			<?php echo $form['declarant.service_douane']->renderLabel() ?><?php echo $form['declarant.service_douane']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_date_saisie">
		<legend>Filtrer par période de saisie</legend>
		<div class="ligne_form select_date">
			<span class="error"><?php echo $form['valide.date_saisie']->renderError() ?></span>
			<?php echo $form['valide.date_saisie']->renderLabel() ?>
			<?php echo $form['valide.date_saisie']->render() ?>
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
	
	<fieldset id="filtre_date_signee">
		<legend>Filtrer par période de signature</legend>
		<div class="ligne_form select_date">
			<span class="error"><?php echo $form['valide.date_signee']->renderError() ?></span>
			<?php echo $form['valide.date_signee']->renderLabel() ?>
			<?php echo $form['valide.date_signee']->render() ?>
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
	
	<fieldset id="filtre_famille">
		<legend>Filtrer par famille</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['declarant.famille']->renderError() ?></span>
			<?php echo $form['declarant.famille']->renderLabel() ?><?php echo $form['declarant.famille']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_mode_saisie">
		<legend>Filtrer par mode de saisie</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['mode_de_saisie']->renderError() ?></span>
			<?php echo $form['mode_de_saisie']->renderLabel() ?><?php echo $form['mode_de_saisie']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_sous_famille">
		<legend>Filtrer par sous famille</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['declarant.sous_famille']->renderError() ?></span>
			<?php echo $form['declarant.sous_famille']->renderLabel() ?><?php echo $form['declarant.sous_famille']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_periode">
		<legend>Filtrer par periode</legend>
		<div class="ligne_form select_periode">
			<span class="error"><?php echo $form['periode']->renderError() ?></span>
			<?php echo $form['periode']->renderLabel() ?><?php echo $form['periode']->render() ?>
		</div>
	</fieldset>
	
	<fieldset id="filtre_campagne">
		<legend>Filtrer par campagne</legend>
		<div class="ligne_form">
			<span class="error"><?php echo $form['campagne']->renderError() ?></span>
			<?php echo $form['campagne']->renderLabel() ?><?php echo $form['campagne']->render() ?>
		</div>
	</fieldset>
	
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>
<?php include_partial('form_collection_template', array('partial' => 'form_produits_item', 'form' => $form->getFormTemplateProduits())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_etablissements_item', 'form' => $form->getFormTemplateEtablissements())); ?>