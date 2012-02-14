<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Etape 4";
	$css_spec = "";
	
	array_push($js_spec, "contrat.js");
	array_push($js_spec_min, "contrat.js");
?>
<?php require('../includes/header.php'); ?>
		<!-- #contenu -->
		<section id="contenu">
			<div id="creation_compte">
				<h1>Récapitulatif</h1>
				
				<div id="recapitulatif">
					<div class="recap_perso">
						<p><span>Nom :</span> <strong>contrat[nom]</strong></p>
						<p><span>Prénom :</span> <strong>contrat[prenom]</strong></p>
						<p><span>Fonction :</span> <strong>contrat[fonction]</strong></p>
						<p><span>Email :</span> <strong>example@example.com</strong></p>
						<p><span>Téléphone :</span> <strong>contrat[telephone]</strong></p>
						<p><span>Fax :</span> <strong>contrat[fax]</strong></p>
					</div>
				
					<div class="recap_etablissement">
						<h2>Etablissement 1 :</h2>
						
						<div class="col">
							<p><span>N° RCS / SIRET:</span> <strong>00000000000000</strong></p>
							<p><span>N° CNI :</span> <strong>00000000000000</strong></p>
							<p><span>N° CVI :</span> <strong>00000000000000</strong></p>
							<p><span>N° accises :</span> <strong>contratetablissement[no_accises]</strong></p>
							<p><span>Nom/Raison Sociale :</span> <strong>contrat[etablissements][0][raison_sociale]</strong></p>
							<p><span>Adresse :</span> <strong>contratetablissement[adresse]</strong></p>
							<p><span>CP :</span> <strong>00000</strong></p>
							<p><span>ville :</span> <strong>contratetablissement[commune]</strong></p>
							<p><span>tel :</span> <strong>contratetablissement[telephone]</strong></p>
							<p><span>fax :</span> <strong>contratetablissement[fax]</strong></p>
							<p><span>email :</span> <strong>example@example.com</strong></p>
						</div>

						<div class="col">
							<p><span>Famille :</span> <strong>Négociant</strong></p>
							<p><span>Sous-famille :</span> <strong>Négociant régional</strong></p>
							
							<div class="adresse_comptabilite">
								<p>Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<p>
								<p><span>Adresse :</span> <strong>contratetablissement[comptabilite_adresse]</strong></p>
								<p><span>CP :</span> <strong>contratetablissement[comptabilite_code_postal]</strong></p>
								<p><span>ville :</span> <strong>contratetablissement[comptabilite_commune]</strong></p>	
							</div>
						</div>

							
						<div class="ligne_btn">
							<a class="btn_ajouter" href="/contrat/etablissement/0/modification/1">Modifier</a>
							<a class="btn_supprimer" href="/contrat/etablissement/0/suppression/1" >Supprimer</a>
						</div>
					</div>

					<div class="recap_etablissement">
						<h2>Etablissement 2 :</h2>
						
						<div class="col">
							<p><span>N° RCS / SIRET:</span> <strong>00000000000000</strong></p>
							<p><span>N° CNI :</span> <strong>00000000000000</strong></p>
							<p><span>N° CVI :</span> <strong>00000000000000</strong></p>
							<p><span>N° accises :</span> <strong>contratetablissement[no_accises]</strong></p>
							<p><span>Nom/Raison Sociale :</span> <strong>contrat[etablissements][0][raison_sociale]</strong></p>
							<p><span>Adresse :</span> <strong>contratetablissement[adresse]</strong></p>
							<p><span>CP :</span> <strong>00000</strong></p>
							<p><span>ville :</span> <strong>contratetablissement[commune]</strong></p>
							<p><span>tel :</span> <strong>contratetablissement[telephone]</strong></p>
							<p><span>fax :</span> <strong>contratetablissement[fax]</strong></p>
							<p><span>email :</span> <strong>example@example.com</strong></p>
						</div>

						<div class="col">
							<p><span>Famille :</span> <strong>Négociant</strong></p>
							<p><span>Sous-famille :</span> <strong>Négociant régional</strong></p>
							<div class="adresse_comptabilite">
								<p>Lieu où est tenue la comptabilité matière (si différente de l'adresse du chai) :<p>
								<p><span>Adresse :</span> <strong>contratetablissement[comptabilite_adresse]</strong></p>
								<p><span>CP :</span> <strong>contratetablissement[comptabilite_code_postal]</strong></p>
								<p><span>ville :</span> <strong>contratetablissement[comptabilite_commune]</strong></p>	
							</div>
						</div>

							
						<div class="ligne_btn">
							<a class="btn_ajouter" href="/contrat/etablissement/0/modification/1">Modifier</a>
							<a class="btn_supprimer" href="/contrat/etablissement/0/suppression/1" >Supprimer</a>
						</div>
					</div>
				
					<div class="ligne_btn">
						<a class="btn_ajouter" href="/contrat/etablissement/nouveau">Nouveau</a>
						<a class="btn_valider" href="/contrat/confirmation">Valider</a>
					</div>
				</div>
			</div>
		</section>
		<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
