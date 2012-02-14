<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Etape 2";
	$css_spec = "";
	
	array_push($js_spec, "contrat.js");
	array_push($js_spec_min, "contrat.js");
?>
<?php require('../includes/header.php'); ?>
		
		<script type="text/javascript">
			var familles = '{"Producteur":["Cave particuli\u00e8re","Cave coop\u00e9rative","Coop\u00e9rateur"],"N\u00e9gociant":["N\u00e9gociant r\u00e9gional","N\u00e9gociant hors r\u00e9gion","Unions de producteurs"],"Vinificateur":["Vinificateur"],"Courtier":["Courtier"]}';
			var sousFamilleSelected = '';
		</script>
		
		<!-- #contenu -->
		<section id="contenu">
			<form id="creation_compte" action="inscription_3.php" method="post">
				<h1>Étape 2 : <strong>Création de compte</strong></h1>
				
				<h2>Veuillez saisir les informations pour l'établissement 1</h2>
				
				<div class="col">
					<div class="ligne_form">
						<label for="champ_1">Raison sociale* :</label>
						<input type="text" id="champ_1" />
					</div>
					<div class="ligne_form">
						<label for="champ_2">SIRET* :</label>
						<input type="text" id="champ_2" />
					</div>
					<div class="ligne_form">
						<label for="champ_3">CNI* :</label>
						<input type="text" id="champ_3" />
					</div>
					<div class="ligne_form">
						<label for="champ_4">CVI* :</label>
						<input type="text" id="champ_4" />
					</div>
					<div class="ligne_form">
						<label for="champ_5">Numéro accises* :</label>
						<input type="text" id="champ_5" />
					</div>
					<div class="ligne_form">
						<label for="champ_6">Numéro TVA intracommunautaire :</label>
						<input type="text" id="champ_6" />
					</div>
					<div class="ligne_form">
						<label for="champ_7">Adresse* :</label>
						<input type="text" id="champ_7" />
					</div>
					<div class="ligne_form">
						<label for="champ_8">Code postal* :</label>
						<input type="text" id="champ_8" />
					</div>
					<div class="ligne_form">
						<label for="champ_9">Commune* :</label>
						<input type="text" id="champ_9" />
					</div>
				</div>
				
				<div class="col">
					<div class="ligne_form">
						<label for="champ_10">Téléphone :</label>
						<input type="text" id="chamchamp_10p_6" />
					</div>
					<div class="ligne_form">
						<label for="champ_11">Fax :</label>
						<input type="text" id="champ_11" />
					</div>
					<div class="ligne_form">
						<label for="champ_12">Email :</label>
						<input type="text" id="champ_12" />
					</div>
					<div class="ligne_form">
						<label for="contratetablissement_famille">Famille*: </label>
						<select id="contratetablissement_famille" name="contratetablissement[famille]">
							<option selected="selected" value=""></option>
							<option value="Producteur">Producteur</option>
							<option value="Négociant">Négociant</option>
							<option value="Vinificateur">Vinificateur</option>
							<option value="Courtier">Courtier</option>
						</select>
					</div>
					<div class="ligne_form">
						<label for="contratetablissement_sous_famille">Sous famille*: </label>
						<select name="contratetablissement[sous_famille]" id="contratetablissement_sous_famille">
							<option value="" selected="selected"></option>
						</select>
						
						<script id="template_options_sous_famille" type="text/x-jquery-tmpl">
							<option value="${value}" {{if selected}}selected="selected"{{/if}} >${value}</option>
						</script>
					</div>
					<div class="ligne_form">
						<ul class="error_list">
							<li>Champ obligatoire</li>
						</ul>
						<label for="champ_15">Service douane* :</label>
						<select id="champ_15">
							<option value="">-</option>
						</select>
					</div>
					
					<div class="ligne_form ligne_form_large">
						<label for="champ_16-1">L'adresse de votre comptabilité est-elle différente de la précédente ?</label>
						<div class="champ_form champ_form_radio_cb">
							<input type="radio" value="Oui" name="adresse_comptabilite" id="champ_16-1">
							<label for="champ_16-1">Oui</label>
							<input type="radio" checked="checked" value="Non" name="adresse_comptabilite" id="champ_16-2">
							<label for="champ_16-2">Non</label>
						</div>
					</div>
					
					
					<div id="adresse_comptabilite">
						<div class="ligne_form">
							<label for="contratetablissement_comptabilite_adresse">Adresse: </label> 
							<input type="text" id="contratetablissement_comptabilite_adresse" name="contratetablissement[comptabilite_adresse]"> 
						</div>
						<div class="ligne_form">
							<label for="contratetablissement_comptabilite_code_postal">Code postal: </label>
							<input type="text" id="contratetablissement_comptabilite_code_postal" name="contratetablissement[comptabilite_code_postal]">
						</div>
						<div class="ligne_form">
							<label for="contratetablissement_comptabilite_commune">Commune: </label> 
							<input type="text" id="contratetablissement_comptabilite_commune" name="contratetablissement[comptabilite_commune]">
						</div>
					</div>
					
					<div class="ligne_btn">
						<button type="submit" class="btn_ajouter">Ajouter</button>
						<button type="submit" class="btn_supprimer">Supprimer</button>
					</div>
				</div>
			</form>
		</section>
		<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
