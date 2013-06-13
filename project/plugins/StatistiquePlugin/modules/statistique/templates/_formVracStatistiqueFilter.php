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
	$( "#<?php echo $form['produit']->renderId() ?>" ).combobox();
	
    var familles = '<?php echo json_encode(EtablissementFamilles::getFamillesForJs()) ?>';
    var sousFamilleSelected = null;
    var famillesJSON = JSON.parse(familles);
    
	var champVendeurFamilles = $("#statistique_vrac_filter_vendeur\\.famille");
	var champVendeurFamillesVal = champVendeurFamilles.val();
	var champVendeurSousFamilles = $("#statistique_vrac_filter_vendeur\\.sous_famille");
	var templateVendeurSousFamilles = $('#template_options_vendeur_sous_famille');
	var champVendeurSousFamillesVal = champVendeurSousFamilles.val();	
	// Si le champ est prérempli
	if(champVendeurFamillesVal)
	{
		$.majVendeurEtablissementSousFamille();
	}
	
	champVendeurFamilles.change(function()
	{
		champVendeurFamillesVal = champVendeurFamilles.val();
		$.majVendeurEtablissementSousFamille();
	});
	
	$.majVendeurEtablissementSousFamille = function()
	{		
		var objTemplate = {};
		
		champVendeurSousFamillesVal = champVendeurSousFamilles.val();
		tabVendeurSousFamilles = famillesJSON[champVendeurFamillesVal];
		champVendeurSousFamilles.html('');
		
		// Parcours des sous-familles
		for(var i in tabVendeurSousFamilles)
		{
			objVendeurTemplate = { value: tabVendeurSousFamilles[i], key: i };
			// Si l'éléments courant doit être sélectionné
			if(champVendeurSousFamillesVal == i)
			{
				$.extend(objTemplate, {selected: true });
			}
			
			// Insertion de l'option
			templateVendeurSousFamilles.tmpl(objVendeurTemplate).appendTo(champVendeurSousFamilles);
		}
	};


	var champAcheteurFamilles = $("#statistique_vrac_filter_acheteur\\.famille");
	var champAcheteurFamillesVal = champAcheteurFamilles.val();
	var champAcheteurSousFamilles = $("#statistique_vrac_filter_acheteur\\.sous_famille");
	var templateAcheteurSousFamilles = $('#template_options_acheteur_sous_famille');
	var champAcheteurSousFamillesVal = champAcheteurSousFamilles.val();	
	// Si le champ est prérempli
	if(champAcheteurFamillesVal)
	{
		$.majAcheteurEtablissementSousFamille();
	}
	
	champAcheteurFamilles.change(function()
	{
		champAcheteurFamillesVal = champAcheteurFamilles.val();
		$.majAcheteurEtablissementSousFamille();
	});
	
	$.majAcheteurEtablissementSousFamille = function()
	{		
		var objAcheteurTemplate = {};
		
		champAcheteurSousFamillesVal = champAcheteurSousFamilles.val();
		tabAcheteurSousFamilles = famillesJSON[champAcheteurFamillesVal];
		champAcheteurSousFamilles.html('');
		
		// Parcours des sous-familles
		for(var i in tabAcheteurSousFamilles)
		{
			objAcheteurTemplate = { value: tabAcheteurSousFamilles[i], key: i };
			// Si l'éléments courant doit être sélectionné
			if(champAcheteurSousFamillesVal == i)
			{
				$.extend(objAcheteurTemplate, {selected: true });
			}
			
			// Insertion de l'option
			templateAcheteurSousFamilles.tmpl(objAcheteurTemplate).appendTo(champAcheteurSousFamilles);
		}
	};
});
</script>
<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques', array('type' => $type)) ?>" method="post">
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
					<div class="ligne_form">
						<span class="error"><?php echo $form['vendeur_identifiant']->renderError() ?></span>
						<?php echo $form['vendeur_identifiant']->renderLabel() ?><?php echo $form['vendeur_identifiant']->render() ?>
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
						<span class="error"><?php echo $form['produit']->renderError() ?></span>
						<?php echo $form['produit']->renderLabel() ?><?php echo $form['produit']->render() ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['acheteur_identifiant']->renderError() ?></span>
						<?php echo $form['acheteur_identifiant']->renderLabel() ?><?php echo $form['acheteur_identifiant']->render() ?>
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
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="ligne_form">
						<span class="error"><?php echo $form['mandataire_identifiant']->renderError() ?></span>
						<?php echo $form['mandataire_identifiant']->renderLabel() ?><?php echo $form['mandataire_identifiant']->render() ?>
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
				        <script id="template_options_vendeur_sous_famille" type="text/x-jquery-tmpl">
            				<option value="${key}" {{if selected}}selected="selected"{{/if}} >${value}</option>
        				</script>
					</div>
				</td>
				<td>
					<div class="ligne_form">
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
						<span class="error"><?php echo $form['acheteur.sous_famille']->renderError() ?></span>
						<?php echo $form['acheteur.sous_famille']->renderLabel() ?><?php echo $form['acheteur.sous_famille']->render() ?>
				        <script id="template_options_acheteur_sous_famille" type="text/x-jquery-tmpl">
            				<option value="${key}" {{if selected}}selected="selected"{{/if}} >${value}</option>
        				</script>
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