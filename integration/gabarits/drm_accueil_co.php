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
		<div id="utilisateur">
			<p><b>jean chapoutier</b> - 8401212132</p>
		</div>
		<h1><?php echo $titre_rub; ?></h1>
		<p id="nombre_etabs">Vous disposez de <b>2</b> établissements. Choisissez celui dans lequel vous voulez entrer.</p>
	
		<!-- #principal -->
		<section id="principal">
			<div id="etablissements_drm">
				<div id="liste_etablissements">
					<ul>
						<li class="etab_courant"><a href="">Etablissement 1 - <span class="surligne">Visualiser</span></a></li>
						<li><a href="">Etablissement 2 - <span class="surligne">Visualiser</span></a></li>
						<li><a href="">Etablissement 3 - <span class="surligne">Visualiser</span></a></li>
					</ul>
				</div>
				<div id="etablissements_infos">
					<table>
						<colgroup>
							<col id="titres">
							<col id="echeance">
							<col id="etat">
							<col id="liens_etabs">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="masque_case">Contrats Vrac</span></th>
								<td><span class="masque_case">Vous n'avez pas d'échéance programmée</span></td>
								<td>&nbsp;</td>
								<td class="derniers">
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr>
								<th><span class="masque_case">DRM</span></th>
								<td><span class="masque_case">Dernière DRM validée le: 18 Juillet</span></td>
								<td class="alerte_forte"><span class="masque_case">Prochaine Echéance: dans 20 jours</span></td>
								<td class="derniers">
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr>
								<th><span class="masque_case">DAI/DS</span></th>
								<td><span class="masque_case">Déclaration en attente de validation</span></td>
								<td class="alerte_moyenne"><span class="masque_case">Validation Attendue : dans 5 jours</span></td>
								<td class="derniers">
									<a href="#" class="btn_accueil_drm"><span>Accueil DRM</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr>
								<th><span class="masque_case">DR</span></th>
								<td><span class="masque_case">Ouverture de la campagne le 1er Octobre</span></td>
								<td>&nbsp;</td>
								<td class="derniers">
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
