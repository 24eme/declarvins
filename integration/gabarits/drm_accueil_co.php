<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Bienvenue sur DeclarVins";
	$titre_page = "Bienvenue sur DeclarVins";
	$rub_courante = "Accueil";
	$css_spec = "";
	
	array_push($js_spec, "drm_accueil.js");
	array_push($js_spec_min, "drm_accueil.js");
?>

<?php require('../includes/header.php'); ?>
	
	<!-- #contenu -->
	<section id="contenu">
		<h1><?php echo $titre_rub; ?></h1>
		<p id="nombre_etabs">Vous disposez de <b>2</b> établissements. Choisissez celui dans lequel vous voulez entrer.</p>
	
		<!-- #principal -->
		<section id="principal">
			<div id="etablissements_drm">
				<div id="liste_etablissements">
					<ul>
						<li><a href=""><span class="visualiser">Visualiser</span></a></li>
						<li><a href=""><span class="visualiser">Visualiser</span></a></li>
						<li><a href=""><span class="visualiser">Visualiser</span></a></li>
					</ul>
				</div>
				<div id="etablissements_infos">
					<table>
						<tbody>
							<tr>
								<th>Contrats Vrac</th>
								<td>Vous n'avez pas d'échéance programmée</td>
								<td>&nbsp;</td>
								<td>
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr>
								<th>DRM</th>
								<td>Dernière DRM validée le: 18 Juillet</td>
								<td>Prochaine Echéance: dans 20 jours</td>
								<td>
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr>
								<th>DAI/DS</th>
								<td>Déclaration en attente de validation</td>
								<td>Validation Attendue : dans 5 jours</td>
								<td>
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr>
								<th>DR</th>
								<td>Ouverture de la campagne le 1er Octobre</td>
								<td>&nbsp;</td>
								<td>
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>
		<!-- fin #principal -->
		
	</section>
	<!-- fin #contenu -->
			
<?php require('../includes/footer.php'); ?>
