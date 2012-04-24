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
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="back_office">
			<a class="btn_ajouter" href="/contrat/etablissement/0/modification/1">Ajouter</a>
		    <h1>Produits</h1>
		    <div class="tableau_ajouts_liquidations">
			    <table class="tableau_recap">
		            <thead>
		    			<tr>
							<th><strong>Cat.</strong></th>
							<th><strong>Dénom.</strong></th>
							<th><strong>Lieu</strong></th>
							<th><strong>Couleur</strong></th>
							<th><strong>Cépage</strong></th>
							<th><strong>Millés.</strong></th>
							<th class="separateur">&nbsp;</th>
							<th><strong>Dép.</strong></th>
							<th><strong>Labels</strong></th>  
							<th><strong>Douane</strong></th> 
							<th><strong>CVO</strong></th>    
							<th><strong>Repli</strong></th>   
							<th><strong>Déclass.</strong></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Beaumes de venise</a></td>
							<td><a href="#" class="editer">Test</a></td>
							<td><a href="#" class="editer">Blanc</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a>2007</td>
							<td class="separateur">&nbsp;</td>
							<td>84</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td>4.5% (L423)</td>
							<td>4.5%</td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Beaumes de venise</a></td>
							<td><a href="#" class="editer">Test</a></td>
							<td><a href="#" class="editer">Rose</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>84</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td>4.5% (L423)</td>
							<td>4.5%</td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Beaumes de venise</a></td>
							<td><a href="#" class="editer">Test</a></td>
							<td><a href="#" class="editer">Rouge</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>84</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td>4.5% (L423)</td>
							<td>4.5%</td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Beaumes de Venise cru</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer">Rouge</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>84</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td>5.5% (L387_AOP)</td>
							<td>5.5%</td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Château Grillet</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer">Blanc</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td></td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td></td>
							<td></td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Châteauneuf du Pape</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer">Blanc</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>84</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td></td>
							<td></td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Châteauneuf du Pape</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer">Rouge</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>84</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td></td>
							<td></td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Clairette de Bellegarde</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer">Blanc</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>30</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td>5% (L387_AOP)</td>
							<td>5%</td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
						<tr>
							<td><a href="#" class="editer">AOP</a></td>
							<td><a href="#" class="editer">Condrieu</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer">Blanc</a></td>
							<td><a href="#" class="editer"></a></td>
							<td><a href="#" class="editer"></a></td>
							<td class="separateur">&nbsp;</td>
							<td>07, 38, 42, 69</td>
							<td>
								<ul>
									<li>AB</li>
									<li>ABC</li>
									<li>AR</li>
									<li>BD</li>
								</ul>
							</td>
							<td>9% (L387_AOP)</td>
							<td>9%</td>	
							<td>ES</td>
							<td>ES</td>
						</tr>
					</tbody>
		    	</table>
			</div>
		</div>
	</section>
</section>	
	
 <?php require('../includes/footer.php'); ?>
