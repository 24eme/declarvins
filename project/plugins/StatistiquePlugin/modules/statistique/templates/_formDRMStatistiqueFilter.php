<style type="text/css">
.popup_form .ligne_form {
	padding:0;
	margin:0;
}
.select_periode select {
	width: 98px !important;
}
.select_date select {
	width: 60px !important;
}
#form_ajout {
	padding: 10px 0;
	margin-bottom: 10px;
}
.popup_form .ligne_form ul.checkbox_list {
	margin: 0;
	padding: 0;
	width: auto;
	height: auto;
	float: none;
	display: block;
}
</style>
<script type="text/javascript">
$( document ).ready(function() {
	$("#filtre_produits_items select").combobox();
});
</script>
<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques_drm') ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	<table>
		<thead>
			<tr>
				<th width="33%">Déclarant</th>
				<th width="33%">Document</th>
				<th width="33%">Produit</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<div id="filtre_etablissements">
						<a class="btn_ajouter_ligne_template" data-container="#filtre_etablissements #filtre_etablissements_items" data-template="#template_form_etablissements_item" href="#"><span>Ajouter</span></a>
						<div id="filtre_etablissements_items">
							<?php foreach ($form['identifiant'] as $formEtablissement): ?>
		                        <?php include_partial('form_etablissements_item', array('form' => $formEtablissement)) ?>
		                    <?php endforeach; ?>
	                    </div>
                    </div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['_id']->renderError() ?></span>
						<?php echo $form['_id']->renderLabel() ?><?php echo $form['_id']->render() ?>
					</div>
				</td>
				<td>
					<div id="filtre_produits">
						<a class="btn_ajouter_ligne_template" data-container="#filtre_produits #filtre_produits_items" data-template="#template_form_produits_item" href="#"><span>Ajouter</span></a>
						<div id="filtre_produits_items">
							<?php foreach ($form['declaration'] as $formProduit): ?>
		                        <?php include_partial('form_produits_item', array('form' => $formProduit)) ?>
		                    <?php endforeach; ?>
	                    </div>
                    </div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['declarant.siege.code_postal']->renderError() ?></span>
						<?php echo $form['declarant.siege.code_postal']->renderLabel() ?><?php echo $form['declarant.siege.code_postal']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['identifiant_drm_historique']->renderError() ?></span>
						<?php echo $form['identifiant_drm_historique']->renderLabel() ?><?php echo $form['identifiant_drm_historique']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['declarant.service_douane']->renderError() ?></span>
						<?php echo $form['declarant.service_douane']->renderLabel() ?><?php echo $form['declarant.service_douane']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form select_date">
						<span class="error"><?php echo $form['valide.date_saisie']->renderError() ?></span>
						<?php echo $form['valide.date_saisie']->renderLabel() ?><?php echo $form['valide.date_saisie']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['declarant.famille']->renderError() ?></span>
						<?php echo $form['declarant.famille']->renderLabel() ?><?php echo $form['declarant.famille']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['mode_de_saisie']->renderError() ?></span>
						<?php echo $form['mode_de_saisie']->renderLabel() ?><?php echo $form['mode_de_saisie']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['declarant.sous_famille']->renderError() ?></span>
						<?php echo $form['declarant.sous_famille']->renderLabel() ?><?php echo $form['declarant.sous_famille']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form select_periode">
						<span class="error"><?php echo $form['periode']->renderError() ?></span>
						<?php echo $form['periode']->renderLabel() ?><?php echo $form['periode']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['campagne']->renderError() ?></span>
						<?php echo $form['campagne']->renderLabel() ?><?php echo $form['campagne']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>
<?php include_partial('form_collection_template', array('partial' => 'form_produits_item', 'form' => $form->getFormTemplateProduits())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_etablissements_item', 'form' => $form->getFormTemplateEtablissements())); ?>