<form id="form_ajout" action="<?php echo url_for('statistiques_drm') ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	
	<!-- .ligne_form -->
	<div class="ligne_form clearfix">	
		<div class="col_form">
			<span class="error"><?php echo $form['_id']->renderError() ?></span>
			<?php echo $form['_id']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['_id']->render() ?>
		</div>

		<div class="col_form">
			<span class="error"><?php echo $form['identifiant_drm_historique']->renderError() ?></span>
			<?php echo $form['identifiant_drm_historique']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['identifiant_drm_historique']->render() ?>
		</div>
	</div>
	<!-- fin .ligne_form -->

	<!-- .ligne_form -->
	<div class="ligne_form clearfix">
		<div class="col_form">
			<div id="filtre_etablissements_items">
				<?php foreach ($form['identifiant'] as $formEtablissement): ?>
					<?php include_partial('form_etablissements_item', array('form' => $formEtablissement)) ?>
				<?php endforeach; ?>
			</div>
			<a class="btn_ajouter_ligne_template" data-container="#filtre_etablissements_items" data-template="#template_form_etablissements_item" href="#">
				<span>Ajouter</span>
			</a>
		</div>

		<div class="col_form">
			<span class="error"><?php echo $form['declarant.famille']->renderError() ?></span>
			<?php echo $form['declarant.famille']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['declarant.famille']->render() ?>
		</div>

		<div class="col_form">
			<span class="error"><?php echo $form['declarant.sous_famille']->renderError() ?></span>
			<?php echo $form['declarant.sous_famille']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['declarant.sous_famille']->render() ?>
		</div>
	</div>
	<!-- fin .ligne_form -->


	<!-- .ligne_form -->
	<div class="ligne_form clearfix">
		<div class="col_form code_postal">
			<span class="error"><?php echo $form['declarant.siege.code_postal']->renderError() ?></span>
			<?php echo $form['declarant.siege.code_postal']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['declarant.siege.code_postal']->render() ?>
		</div>

		<div class="col_form">
			<span class="error"><?php echo $form['declarant.service_douane']->renderError() ?></span>
			<?php echo $form['declarant.service_douane']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['declarant.service_douane']->render() ?>
		</div>
	</div>
	<!-- fin .ligne_form -->

	<!-- .ligne_form -->
	<div class="ligne_form clearfix">
		<div class="col_form select_periode">
			<span class="error"><?php echo $form['periode']->renderError() ?></span>
			<?php echo $form['periode']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['periode']->render() ?>
		</div>
		<div class="col_form">
			<span class="error"><?php echo $form['campagne']->renderError() ?></span>
			<?php echo $form['campagne']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['campagne']->render() ?>
		</div>
	</div>
	<!-- fin .ligne_form -->

	<!-- .ligne_form -->
	<div class="ligne_form clearfix">
		<div class="col_form select_date">
			<span class="error"><?php echo $form['valide.date_saisie']->renderError() ?></span>
			<?php echo $form['valide.date_saisie']->renderLabel(null, array('class' => 'intitule_champ')) ?>
			<?php echo $form['valide.date_saisie']->render() ?>
		</div>

		<div class="col_form select_date">
			<span class="error"><?php echo $form['valide.date_signee']->renderError() ?></span>
			<?php echo $form['valide.date_signee']->renderLabel(null, array('class' => 'intitule_champ')) ?>
			<?php echo $form['valide.date_signee']->render() ?>
		</div>

		<div class="col_form">
			<span class="error"><?php echo $form['mode_de_saisie']->renderError() ?></span>
			<?php echo $form['mode_de_saisie']->renderLabel(null, array('class' => 'intitule_champ')) ?><?php echo $form['mode_de_saisie']->render() ?>
		</div>
	</div>
	<!-- fin .ligne_form -->

	<!-- ligne_form -->
	<div class="ligne_form clearfix">
		<div id="filtre_produits_items">
			<?php foreach ($form['declaration'] as $formProduit): ?>
				<?php include_partial('form_produits_item', array('form' => $formProduit)) ?>
			<?php endforeach; ?>
		</div>
		<a class="btn_ajouter_ligne_template" data-container="#filtre_produits_items" data-template="#template_form_produits_item" href="#">
			<span>Ajouter</span>
		</a>
	</div>
	<!-- fin .ligne_form -->

	
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>
<?php include_partial('form_collection_template', array('partial' => 'form_produits_item', 'form' => $form->getFormTemplateProduits())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_etablissements_item', 'form' => $form->getFormTemplateEtablissements())); ?>