<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Etape 2";
	$css_spec = "inscription.less";
	$js_spec = "";
?>
<?php require('../includes/header.php'); ?>
		
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
						<label for="champ_13">Famille* :</label>
						<select id="champ_13">
							<option value="">-</option>
						</select>
					</div>
					<div class="ligne_form">
						<label for="champ_14">Sous-famille* :</label>
						<select id="champ_14">
							<option value="">-</option>
						</select>
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
							<input type="radio" id="champ_16-1" name="champ_16" />
							<label for="champ_16-1">Oui</label>
							<input type="radio" id="champ_16-2" name="champ_16" />
							<label for="champ_16-2">Nom</label>
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
