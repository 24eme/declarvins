<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "Déclaration Récapitulative Mensuelle";
	$rub_courante = "DRM";
	$css_spec = "";
	
	array_push($js_spec, "drm.js");
	array_push($js_spec_min, "drm.js");
?>

<?php require('../includes/header.php'); ?>
	
	<?php require('../includes/nav_principale.php'); ?>
	
	<!-- #contenu -->
	<section id="contenu">
		<h1><?php echo $titre_rub; ?></h1>
		<p class="intro">Bienvenue sur votre espace DRM. Que souhaitez-vous faire ?</p>
	
		<!-- #principal -->
		<section id="principal">
			
			<div id="recap_drm">
				<div id="drm_annee_courante">
					<table>
						<thead>
							<tr>
								<th>DRM</th>
								<th>Etat validation</th>
								<th>Etat réception</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Novembre 2011</td>
								<td class="msg">En cours</td>
								<td>&nbsp;</td>
								<td>
									<a href="#" class="btn_visualiser"><span>Visualiser</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<tr class="alt">
								<td>Octobre 2011</td>
								<td>OK</td>
								<td class="msg">Saisie IR</td>
								<td>
									<a href="#" class="btn_visualiser"><span>Visualiser</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<?php for($i=0; $i<9; $i++) { ?>
							<?php if($i%2!=0) $class=' class="alt"'; else $class = ''; ?>
							<tr <?php echo $class; ?>>
								<td>Septembre 2011</td>
								<td>OK</td>
								<td>OK</td>
								<td>
									<a href="#" class="btn_visualiser"><span>Visualiser</span></a>
									<a href="#" class="btn_declarer"><span>Déclarer</span></a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				
				<div id="drm_anciennes">
					<h2><span><em>Afficher</em> les drm 2009/2010</span></h2>
					<div class="table_drm">
						<table>
							<thead>
								<tr>
									<th>DRM</th>
									<th>Etat validation</th>
									<th>Etat réception</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php for($i=0; $i<11; $i++) { ?>
								<?php if($i%2!=0) $class=' class="alt"'; else $class = ''; ?>
								<tr <?php echo $class; ?>>
									<td>Décembre 2010</td>
									<td>OK</td>
									<td>OK</td>
									<td>
										<a href="#" class="btn_visualiser"><span>Visualiser</span></a>
										<a href="#" class="btn_declarer"><span>Déclarer</span></a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>


					<h2><span><em>Afficher</em> les drm 2008/2009</span></h2>

					<div class="table_drm">
						<table>
							<thead>
								<tr>
									<th>DRM</th>
									<th>Etat validation</th>
									<th>Etat réception</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php for($i=0; $i<11; $i++) { ?>
								<?php if($i%2!=0) $class=' class="alt"'; else $class = ''; ?>
								<tr <?php echo $class; ?>>
									<td>Décembre 2010</td>
									<td>OK</td>
									<td>OK</td>
									<td>
										<a href="#" class="btn_visualiser"><span>Visualiser</span></a>
										<a href="#" class="btn_declarer"><span>Déclarer</span></a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<ul id="nav_drm_annees">
				<li class="actif"><strong>DRM 2011/2012</strong></li>
				<li><a href="#">DRM 2010/2011</a></li>
				<li><a href="#">DRM 2009/2010</a></li>
				<li><a href="#">DRM 2008/2009</a></li>
				<li><a href="#">DRM 2007/2008</a></li>
			</ul>
		</section>
		<!-- fin #principal -->
	
	</section>
	<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
