<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Etape 1";
	$css_spec = "";
	
	array_push($js_spec, "contrat.js");
	array_push($js_spec_min, "contrat.js");
?>
<?php require('../includes/header.php'); ?>
		
		
		<!-- #contenu -->
		<section id="contenu">
			<form id="creation_compte" action="inscription_2.php" method="post">
				<input type="hidden" value="1" name="nb_etablissement" id="contrat_nb_etablissement">
				
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
				</div>
				
				<div class="col">
					<div class="ligne_form">
						<label for="champ_4">Téléphone* :</label>
						<input type="text" id="champ_4" />
					</div>
					<div class="ligne_form">
						<label for="champ_5">Fax* :</label>
						<input type="text" id="champ_5" />
					</div>	
				</div>
				
				<div id="infos_etablissements">
					<h2>Etablissement</h2>
					
					<a href="#" id="btn_ajouter_etablissement">Ajouter <span>un établissement</span></a>
					
					<script id="template_etablissement" type="text/x-jquery-tmpl">
						<div id="etablissement${nbEtablissements}" class="etablissement">
							<div class="ligne_form">
								<label for="contrat_etablissements_${nbEtablissements}_raison_sociale">Raison sociale*: </label>
								<input type="text" id="contrat_etablissements_${nbEtablissements}_raison_sociale" name="contrat[etablissements][${nbEtablissements}][raison_sociale]">
							</div>
							<div class="ligne_form">
								<label for="contrat_etablissements_${nbEtablissements}_siret_cni">SIRET/CNI*: </label>
								<input type="text" id="contrat_etablissements_${nbEtablissements}_siret_cni" name="contrat[etablissements][${nbEtablissements}][siret_cni]">
							</div>
							<a href="#" class="supprimer">Supprimer</a>
						</div>
					</script>
								
					<div id="etablissement0" class="etablissement">
						<div class="ligne_form">
							<label for="contrat_etablissements_0_raison_sociale">Raison sociale*: </label>
							<input type="text" id="contrat_etablissements_0_raison_sociale" name="contrat[etablissements][0][raison_sociale]">
						</div>
						<div class="ligne_form">
							<label for="contrat_etablissements_0_siret_cni">SIRET/CNI*: </label>
							<input type="text" id="contrat_etablissements_0_siret_cni" name="contrat[etablissements][0][siret_cni]">
						</div>
					</div>
				</div>
				
				<div class="ligne_btn">
					<button type="submit" class="btn_valider">Valider</button>
				</div>
			</form>
		</section>
		<!-- fin #contenu -->
		
		
<?php require('../includes/footer.php'); ?>
