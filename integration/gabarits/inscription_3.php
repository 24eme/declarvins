<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Etape 3";
	$css_spec = "inscription.less";
	$js_spec = "";
?>
<?php require('../includes/header.php'); ?>
		
		<!-- #contenu -->
		<section id="contenu">
			<form id="creation_compte" action="inscription_1.php" method="post">
				<h1>Étape 3 : <strong>Création de compte</strong></h1>
				
				<div class="col">
					<div class="ligne_form">
						<label for="champ_1">Identifiant* :</label>
						<input type="text" id="champ_1" />
					</div>
					<div class="ligne_form">
						<label for="champ_2">Mot de passe* :</label>
						<input type="password" id="champ_2" />
					</div>
					<div class="ligne_form">
						<label for="champ_3">Vérification du mot de passe* :</label>
						<input type="password" id="champ_3" />
					</div>
					<div class="ligne_form">
						<ul class="error_list">
							<li>Champ obligatoire</li>
						</ul>
						<label for="champ_4">Adresse e-mail* :</label>
						<input type="text" id="champ_4" />
					</div>
					<div class="ligne_form">
						<label for="champ_5">Vérification de l'e-mail* :</label>
						<input type="text" id="champ_5" />
					</div>
					
					<div class="ligne_btn">
						<button type="submit" class="btn_valider">Valider</button>
					</div>
				</div>
			</form>
		</section>
		<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
