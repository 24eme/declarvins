<?php require('../config/inc.php'); ?>
<?php 
	$titre_rub = "Inscription";
	$rub_courante = "inscription";
	$titre_page = "Création de compte";
	$css_spec = "";
	
	array_push($js_spec, "contrat.js");
	array_push($js_spec_min, "contrat.js");
?>
<?php require('../includes/header.php'); ?>
		
		<!-- #contenu -->
		<section id="contenu">
			<div id="creation_compte">
				<h1>Création de compte</h1>
				
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut arcu sit amet eros pharetra condimentum. Mauris pretium est quis dolor suscipit tristique. Cras sit amet sodales ligula. Suspendisse purus magna, posuere et rhoncus a, lacinia id massa. Proin facilisis dapibus metus. Nunc bibendum, metus laoreet auctor aliquam, orci metus placerat massa, ac tristique elit dui id nisi. Pellentesque et neque in diam sagittis euismod at id purus. Quisque gravida sollicitudin convallis. Integer lectus felis, feugiat vel pulvinar ac, commodo vitae ante. Curabitur tristique ullamcorper nisl vitae mollis. </p>
				
				
				<div class="ligne_btn">
					<a href="inscription_1.php" class="btn_valider">Valider</a>
				</div>
			</div>
		</section>
		<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
