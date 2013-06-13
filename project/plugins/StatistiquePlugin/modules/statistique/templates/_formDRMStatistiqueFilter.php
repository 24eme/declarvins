<style type="text/css">
.popup_form .ligne_form {
	padding:0;
	margin:0;
}
#statistique_drm_filter_periode_year, #statistique_drm_filter_periode_month {
	width: 98px;
}
#form_ajout {
	padding: 10px 0;
	margin-bottom: 10px;
}
</style>
<script type="text/javascript">
$( document ).ready(function() {
	$( "#statistique_drm_filter_periode_day" ).hide();
	$( "#statistique_drm_filter_periode_day" ).val(1);
	$( "#<?php echo $form['declaration']->renderId() ?>" ).combobox();
	
    var familles = '<?php echo json_encode(EtablissementFamilles::getFamillesForJs()) ?>';
    var sousFamilleSelected = '<?php echo $form['declarant.sous_famille']->getValue() ?>';

    var famillesJSON = JSON.parse(familles);
	var champFamilles = $("#statistique_drm_filter_declarant\\.famille");
	var champFamillesVal = champFamilles.val();
	var champSousFamilles = $("#statistique_drm_filter_declarant\\.sous_famille");
	var templateSousFamilles = $('#template_options_sous_famille');
	var champSousFamillesVal = champSousFamilles.val();	
	// Si le champ est prérempli
	if(champFamillesVal)
	{
		$.majEtablissementSousFamille();
	}
	
	champFamilles.change(function()
	{
		champFamillesVal = champFamilles.val();
		$.majEtablissementSousFamille();
	});
	
	$.majEtablissementSousFamille = function()
	{		
		var objTemplate = {};
		
		champSousFamillesVal = champSousFamilles.val();
		tabSousFamilles = famillesJSON[champFamillesVal];
		champSousFamilles.html('');
		
		// Parcours des sous-familles
		for(var i in tabSousFamilles)
		{
			objTemplate = { value: tabSousFamilles[i], key: i };
			// Si l'éléments courant doit être sélectionné
			if(champSousFamillesVal == i)
			{
				$.extend(objTemplate, {selected: true });
			}
			
			// Insertion de l'option
			templateSousFamilles.tmpl(objTemplate).appendTo(champSousFamilles);
		}
	};
});
</script>
<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques', array('type' => $type)) ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	<table>
		<thead>
			<tr>
				<th>Déclarant</th>
				<th>Document</th>
				<th>Produit</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['identifiant']->renderError() ?></span>
						<?php echo $form['identifiant']->renderLabel() ?><?php echo $form['identifiant']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['_id']->renderError() ?></span>
						<?php echo $form['_id']->renderLabel() ?><?php echo $form['_id']->render() ?>
					</div>
				</td>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['declaration']->renderError() ?></span>
						<?php echo $form['declaration']->renderLabel() ?><?php echo $form['declaration']->render() ?>
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
					<div class="ligne_form">
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
						<span class="error"><?php echo $form['valide.date_signee']->renderError() ?></span>
						<?php echo $form['valide.date_signee']->renderLabel() ?><?php echo $form['valide.date_signee']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['declarant.sous_famille']->renderError() ?></span>
						<?php echo $form['declarant.sous_famille']->renderLabel() ?><?php echo $form['declarant.sous_famille']->render() ?>
				        <script id="template_options_sous_famille" type="text/x-jquery-tmpl">
            				<option value="${key}" {{if selected}}selected="selected"{{/if}} >${value}</option>
        				</script>
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
					<div class="ligne_form">
						<span class="error"><?php echo $form['periode']->renderError() ?></span>
						<?php echo $form['periode']->renderLabel() ?><?php echo $form['periode']->render() ?>
					</div>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>