<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Etape 1";
	$css_spec = "inscription.less";
	$js_spec = "";
?>
<?php require('../includes/header.php'); ?>
		
		<!-- #contenu -->
		<section id="contenu">
			<form id="creation_compte" action="inscription_2.php" method="post">
				<h1>Étape 1 : <strong>Création de compte</strong></h1>
				
				<div class="col">
					<div class="ligne_form">
						<ul class="error_list">
							<li>Champ obligatoire</li>
						</ul>
						<label for="champ_1">Nom* :</label>
						<input type="text" id="champ_1" />
					</div>
					<div class="ligne_form">
						<label for="champ_2">Prénom* :</label>
						<input type="text" id="champ_2" />
					</div>
					<div class="ligne_form">
						<label for="champ_3">Fonction* :</label>
						<input type="text" id="champ_3" />
					</div>
					<div class="ligne_form">
						<label for="champ_4">Téléphone* :</label>
						<input type="text" id="champ_4" />
					</div>
					<div class="ligne_form">
						<label for="champ_5">Fax* :</label>
						<input type="text" id="champ_5" />
					</div>
				</div>
				
				<div class="col">
					<h2>Etablissement</h2>
					<div class="ligne_form">
						<label for="champ_6">Raison sociale* :</label>
						<input type="text" id="champ_6" />
					</div>
					<div class="ligne_form">
						<ul class="error_list">
							<li>Champ obligatoire</li>
						</ul>
						<label for="champ_7">SIRET/CNI* :</label>
						<input type="text" id="champ_7" />
					</div>
					<div class="ligne_form ligne_form_large">
						<label for="champ_8-1">Souhaitez-vous représenter d'autres établissements ?</label>
						<div class="champ_form champ_form_radio_cb">
							<input type="radio" id="champ_8-1" name="champ_8" />
							<label for="champ_8-1">Oui</label>
							<input type="radio" id="champ_8-2" name="champ_8" />
							<label for="champ_8-2">Nom</label>
						</div>
					</div>
					
					<div class="ligne_btn">
						<button type="submit" class="btn_valider">Valider</button>
					</div>
				</div>
			</form>
		</section>
		<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
