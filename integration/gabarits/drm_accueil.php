<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "Déclaration Récapitulative Mensuelle";
	$rub_courante = "DRM";
	$css_spec = "";
	
	//array_push($js_spec, "");
	//array_push($js_spec_min, "");
?>

<?php require('../includes/header.php'); ?>
	
	<?php require('../includes/nav_principale.php'); ?>
	
	<!-- #contenu -->
	<section id="contenu" class="gabarit_2">
	
		<h1><?php echo $titre_rub; ?></h1>
		
		<p class="nom_drm">DRM 2011 - Mars</p>
		
		<div class="clearfix" id="statut_declaration">
			<nav id="declaration_etapes">
				<ol>
					<li class="premier passe">
						<a href="#">1. Informations</a>
					</li>
					<li class="passe">
						<a href="#">2. Surfaces et faire valoir</a>
					</li>
					<li class="actif">
						<span>3. Récolte</span>
					</li>
					<li>
						<span>4. Récapitulatif</span>
					</li>
					<li class="dernier">
						<span>5. Validation</span>
					</li>
				</ol>
			</nav>
						
			<!-- #etat_avancement -->
			<div id="etat_avancement">
				<p>État d'avancement <strong>20<span>%</span></strong></p>
				<div id="barre_avancement">
					<div style="width: 20%"></div>
				</div>
			</div>
			<!-- fin #etat_avancement -->
		</div>
		
		<!-- #principal -->
		<section id="principal">
			<h1><?php echo $titre_rub; ?></h1>
			<p class="intro">Bienvenue sur votre espace DRM. Que souhaitez-vous faire ?</p>
			
			<ul>
				<li><a href="#">Accéder à mon historique de DRM</a></li>
				<li><a href="#">Saisir ma DRM en Cours</a></li>
				<li><a href="#">Soumettre une DRM Rectificative</a></li>
			</ul>
		</section>
		<!-- fin #principal -->
		
			
		<!-- #colonne -->
		<aside id="colonne">
			<div id="docs_aide" class="bloc_col">
				<h2>Documents d'aide</h2>
				<ul>
					<li class="notice_generale"><a href="#">Accédez à la notice générale</a></li>
					<li class="echeances_declaration"><a href="#">Echéances de déclaration</a></li>
					<li class="rdt_infos_legales"><a href="#">Rendements &amp; Informations légales</a></li>
				</ul>
			</div>
			
			<div id="docs_aide" class="bloc_col">
				<h2>Outils</h2>
				<ul>
					<li class="infos_structure"><a href="#">Modifier mes infos de structure</a></li>
					<li class="tutoriel"><a href="#">Tutoriel</a></li>
					<li class="contact"><a href="#">Contactez-nous</a></li>
					<li class="drm_rectificative"><a href="#">Soumettre une DRM rectificative</a></li>
				</ul>
			</div>
		</aside>
		<!-- fin #colonne -->
	
	</section>
	<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
