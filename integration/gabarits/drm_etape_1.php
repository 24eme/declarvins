<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "DRM 2011 - MARS";
	$rub_courante = "DRM";
	$declaration_etape = 3;
	$declaration_avancement = 10;
	$css_spec = "";
	
	array_push($js_spec, "drm.js");
	array_push($js_spec_min, "drm.js");
?>

<?php require('../includes/header.php'); ?>
	
	<?php require('../includes/nav_principale.php'); ?>
	
	<!-- #contenu -->
	<section id="contenu">
		<h1><?php echo $titre_rub; ?></h1>
		<p id="date_drm"><?php echo $titre_page; ?></p>
	
		<?php require('../includes/statut_declaration.php'); ?>
	
		<!-- #principal -->
		<section id="principal">
			<div id="application_dr">
				<div id="drm_informations">
					<p>Veuillez tout d'abord confirmer les informations ci-dessous :</p>

					<form action="#" method="post">

						<div class="ligne_form">
							<label for="champ_1">CVI :</label>
							<input type="hidden" value="840121213" id="champ_1" />
							<span class="valeur">840121213</span>
						</div>

						<div class="ligne_form">
							<label for="champ_2">N° SIRET :</label>
							<input type="hidden" value="78320555200012" id="champ_2" />
							<span class="valeur">78320555200012</span>
						</div>

						<div class="ligne_form">
							<label for="champ_3">N° entrepositaire agréé :</label>
							<input type="hidden" value="FR093027E0323" id="champ_3" />
							<span class="valeur">FR093027E0323</span>
						</div>


						<div class="ligne_form">
							<label for="champ_4">Nom :</label>
							<input type="hidden" value="DE BALMA" id="champ_4" />
							<span class="valeur valeur_2">DE BALMA</span>
						</div>

						<div class="ligne_form">
							<label for="champ_5">Raison Sociale :</label>
							<input type="hidden" value="SCA VIGNERONS DE BALMA" id="champ_5" />
							<span class="valeur">SCA VIGNERONS DE BALMA</span>
						</div>

						<div class="ligne_form">
							<label for="champ_6">Adresse du chai :</label>
							<input type="hidden" value="VENITIA, Quartier Ravel 84190 BEAUMES DE VENISE" id="champ_6" />
							<span class="valeur">VENITIA, Quartier Ravel<br /> 84190 BEAUMES DE VENISE</span>
						</div>

						<div class="ligne_form">
							<label for="champ_7">Lieu ou est tenue la comptabilité matière :</label>
							<input type="hidden" value="VENITIA, Quartier Ravel 84190 BEAUMES DE VENISE" id="champ_7" />
							<span class="valeur">IDEM</span>
						</div>

						<div class="ligne_form">
							<label for="champ_8">Service des douanes :</label>
							<input type="hidden" value="Bagnols" id="champ_8" />
							<span class="valeur">Bagnols</span>
						</div>

						<div class="ligne_form">
							<label for="champ_9">Numéro d’Accise :</label>
							<input type="hidden" value="1654546764" id="champ_9" />
							<span class="valeur">1654546764</span>
						</div>

						<div class="ligne_form">
							<label for="champ_conf_infos">Je confirme l'exactitude de ces informations</label>
							<input type="radio" id="champ_conf_infos" name="champ_10" />
						</div>

						<div class="ligne_form">
							<label for="champ_modif_infos">Je souhaite modifier mes informations de structure</label>
							<input type="radio" id="champ_modif_infos" name="champ_10" data-popup-trigger="true" />
						</div>

						<div class="ligne_btn">
							<button type="submit" class="btn_valider">Valider</button>
							
							<a href="#" class="btn_popup btn_popup_trigger" data-popup="#popup_confirm_modif_infos" data-popup-config="configConfirmModifInfos" data-popup-titre="Etes-vous sûr de vouloir modifier ces informations ?"></a>
						</div>
					</form>

				</div>
			</div>
		</section>
		<!-- fin #principal -->

		<?php require('../includes/_popup_confirm_modif_infos.php'); ?>
		
	</section>
	<!-- fin #contenu -->
			
<?php require('../includes/footer.php'); ?>
