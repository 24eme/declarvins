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
<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques_vrac') ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	<table>
		<thead>
			<tr>
				<th>Acteurs</th>
				<th>Document</th>
				<th>Produit</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<div id="filtre_vendeur_identifiant">
						<a class="btn_ajouter_ligne_template" data-container="#filtre_vendeur_identifiant #filtre_vendeur_identifiant_items" data-template="#template_form_vendeur_identifiant_item" href="#"><span>Ajouter</span></a>
						<div id="filtre_vendeur_identifiant_items">
							<?php foreach ($form['vendeur_identifiant'] as $subForm): ?>
		                        <?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Vendeur : ')) ?>
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
							<?php foreach ($form['produit'] as $formProduit): ?>
		                        <?php include_partial('form_produits_item', array('form' => $formProduit)) ?>
		                    <?php endforeach; ?>
	                    </div>
                    </div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="filtre_acheteur_identifiant">
						<a class="btn_ajouter_ligne_template" data-container="#filtre_acheteur_identifiant #filtre_acheteur_identifiant_items" data-template="#template_form_acheteur_identifiant_item" href="#"><span>Ajouter</span></a>
						<div id="filtre_acheteur_identifiant_items">
							<?php foreach ($form['acheteur_identifiant'] as $subForm): ?>
		                        <?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Acheteur : ')) ?>
		                    <?php endforeach; ?>
	                    </div>
                    </div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['cas_particulier']->renderError() ?></span>
						<?php echo $form['cas_particulier']->renderLabel() ?><?php echo $form['cas_particulier']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['millesime']->renderError() ?></span>
						<?php echo $form['millesime']->renderLabel() ?><?php echo $form['millesime']->render() ?>
						<br /><i style="font-size: 10px;">Multiple, utilisez le séparateur ;</i>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="filtre_mandataire_identifiant">
						<a class="btn_ajouter_ligne_template" data-container="#filtre_mandataire_identifiant #filtre_mandataire_identifiant_items" data-template="#template_form_mandataire_identifiant_item" href="#"><span>Ajouter</span></a>
						<div id="filtre_mandataire_identifiant_items">
							<?php foreach ($form['mandataire_identifiant'] as $subForm): ?>
		                        <?php include_partial('form_etablissements_item', array('form' => $subForm, 'label' => 'Courtier : ')) ?>
		                    <?php endforeach; ?>
	                    </div>
                    </div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['type_transaction']->renderError() ?></span>
						<?php echo $form['type_transaction']->renderLabel() ?><?php echo $form['type_transaction']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['labels']->renderError() ?></span>
						<?php echo $form['labels']->renderLabel() ?><?php echo $form['labels']->render() ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['vendeur.code_postal']->renderError() ?></span>
						<?php echo $form['vendeur.code_postal']->renderLabel() ?><?php echo $form['vendeur.code_postal']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['export']->renderError() ?></span>
						<?php echo $form['export']->renderLabel() ?><?php echo $form['export']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['mentions']->renderError() ?></span>
						<?php echo $form['mentions']->renderLabel() ?><?php echo $form['mentions']->render() ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['acheteur.code_postal']->renderError() ?></span>
						<?php echo $form['acheteur.code_postal']->renderLabel() ?><?php echo $form['acheteur.code_postal']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['annexe']->renderError() ?></span>
						<?php echo $form['annexe']->renderLabel() ?><?php echo $form['annexe']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['vendeur.famille']->renderError() ?></span>
						<?php echo $form['vendeur.famille']->renderLabel() ?><?php echo $form['vendeur.famille']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['type_prix']->renderError() ?></span>
						<?php echo $form['type_prix']->renderLabel() ?><?php echo $form['type_prix']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['vendeur.sous_famille']->renderError() ?></span>
						<?php echo $form['vendeur.sous_famille']->renderLabel() ?><?php echo $form['vendeur.sous_famille']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form select_date">
						<span class="error"><?php echo $form['date_limite_retiraison']->renderError() ?></span>
						<?php echo $form['date_limite_retiraison']->renderLabel() ?><?php echo $form['date_limite_retiraison']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['acheteur.famille']->renderError() ?></span>
						<?php echo $form['acheteur.famille']->renderLabel() ?><?php echo $form['acheteur.famille']->render() ?>
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
						<span class="error"><?php echo $form['acheteur.sous_famille']->renderError() ?></span>
						<?php echo $form['acheteur.sous_famille']->renderLabel() ?><?php echo $form['acheteur.sous_famille']->render() ?>
					</div>
				</td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>
<?php include_partial('form_collection_template', array('partial' => 'form_produits_item', 'form' => $form->getFormTemplateProduits())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_vendeur_identifiant_item', 'form' => $form->getFormTemplateVendeurIdentifiant())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_acheteur_identifiant_item', 'form' => $form->getFormTemplateAcheteurIdentifiant())); ?>
<?php include_partial('form_collection_template', array('partial' => 'form_mandataire_identifiant_item', 'form' => $form->getFormTemplateMandataireIdentifiant())); ?>