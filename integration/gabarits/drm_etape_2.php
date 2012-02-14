<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "DRM 2011 - MARS";
	$rub_courante = "DRM";
	$declaration_etape = 2;
	$declaration_avancement = 20;
	$css_spec = "";
	
	array_push($js_spec, "drm.js", "declaration.js");
	array_push($js_spec_min, "drm.js", "declaration.js");
?>

<?php require('../includes/header.php'); ?>
	
	<?php require('../includes/nav_principale.php'); ?>
	
	<!-- #contenu -->
	<section id="contenu">
		<h1><?php echo $titre_rub; ?></h1>
		<p id="date_drm">DRM 2011 - MARS</p>
	
		<?php require('../includes/statut_declaration.php'); ?>
	
		<!-- #principal -->
		<section id="principal">
			<div id="application_dr">
				<ul id="onglets_principal">
					<li class="actif"><strong>Mouvements Généraux</strong></li>
				</ul>
			
				<div id="contenu_onglet">
					<a href="#" class="btn_passer_etape">Passer cette étape</a>	
					<p class="intro">Au cours du mois écoulé, avez-vous connu des changements de structure particuliers ?</p>
					
					<div id="ajouts_liquidations">
						<!-- #tableau_aop -->
						<div id="tableau_aop" class="tableau_ajouts_liquidations">
							<h2>AOP</h2>
							
							<div class="recap_produit">
								<table class="tableau_recap">
									<thead>
										<tr>
											<th>Appelation</th>
											<th>Couleur</th>
											<th>Label</th>
											<th>Disponible</th>
											<th>Stock vide</th>
											<th>Pas de mouvement</th>
										</tr>
									</thead>
									<tbody>
										<?php for($i=0; $i<6; $i++) { ?>
										<tr>					     
											<td>
												<a href="#?action=supprimer_aop&id=<?php echo $i; ?>" class="supprimer">Supprimer</a>
												Côtes du Rhône
												<input type="hidden" name="aop_id" value="<?php echo $i; ?>" />
											</td>
											<td>Rouge</td>
											<td>Label</td>
											<td class="disponible">
												<input type="hidden" name="disponible" value="0" />
												0HL
											</td>
											<!-- Si disponible > 0 alors disable stock vide -->
											<td class="stock_vide">
												<form method="post" action="/drm/mouvements-generaux/AOP/CVP/update/<?php echo $i; ?>" class="updateProduct">
													<input type="hidden" id="produit_<?php echo $i; ?>__csrf_token" value="6e9e93d2c2f2afcbf8cd24d7fa693113" name="produit_<?php echo $i; ?>[_csrf_token]">
													<input type="checkbox" id="produit_<?php echo $i; ?>_stock_vide" name="produit_<?php echo $i; ?>[stock_vide]">
												</form>
											</td>
											<td class="pas_mouvement">
												<form method="post" action="/drm/mouvements-generaux/AOP/CVP/update/<?php echo $i; ?>" class="updateProduct">
													<input type="hidden" id="produit_<?php echo $i; ?>__csrf_token" value="6e9e93d2c2f2afcbf8cd24d7fa693113" name="produit_<?php echo $i; ?>[_csrf_token]">
													<input type="checkbox" id="produit_<?php echo $i; ?>_pas_de_mouvement" name="produit_<?php echo $i; ?>[pas_de_mouvement]">
												</form>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<div class="btn">
									<a href="../includes/_popup_ajout_produit_AOP.php" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_produit_AOP" data-popup-config="configAjoutProduit">Ajouter un nouveau produit</a>
								</div>
							</div>
						</div>
						<!-- #tableau_aop -->
						
						<!-- #tableau_igp -->
						<div id="tableau_igp" class="tableau_ajouts_liquidations">
							<h2>IGP</h2>
							
							<div class="recap_produit">
								<table class="tableau_recap">
									<thead>
										<tr>
											<th>Dénomination</th>
											<th>Couleur</th>
											<th>Label</th>
											<th>Disponible</th>
											<th>Stock vide</th>
											<th>Pas de mouvement</th>
										</tr>
									</thead>
									<tbody>
										<?php for($i=0; $i<6; $i++) { ?>
										<tr>					     
											<td>
												<a href="#?action=supprimer_aop&id=<?php echo $i; ?>" class="supprimer">Supprimer</a>
												Côtes du Rhône
												<input type="hidden" name="aop_id" value="<?php echo $i; ?>" />
											</td>
											<td>Rouge</td>
											<td>Label</td>
											<td class="disponible">
												<input type="hidden" name="disponible" value="87">
												87HL
											</td>
											<!-- Si disponible > 0 alors disable stock vide -->
											<td class="stock_vide">
												<form method="post" action="/drm/mouvements-generaux/IGP/D83/update/<?php echo $i; ?>" class="updateProduct">
													<input type="hidden" id="produit_<?php echo $i; ?>__csrf_token" value="6e9e93d2c2f2afcbf8cd24d7fa693113" name="produit_<?php echo $i; ?>[_csrf_token]">
													<input type="checkbox" id="produit_<?php echo $i; ?>_stock_vide" name="produit_<?php echo $i; ?>[stock_vide]">
												</form>
											</td>
											<td class="pas_mouvement">
												<form method="post" action="/drm/mouvements-generaux/IGP/D83/update/<?php echo $i; ?>" class="updateProduct">
													<input type="hidden" id="produit_<?php echo $i; ?>__csrf_token" value="6e9e93d2c2f2afcbf8cd24d7fa693113" name="produit_<?php echo $i; ?>[_csrf_token]">
													<input type="checkbox" id="produit_<?php echo $i; ?>_pas_de_mouvement" name="produit_<?php echo $i; ?>[pas_de_mouvement]">
												</form>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<div class="btn">
									<a href="../includes/_popup_ajout_produit_IGP.php" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_produit_IGP" data-popup-config="configAjoutProduit">Ajouter un nouveau produit</a>
								</div>
							</div>
						</div>
						<!-- #tableau_igp -->
						
						<!-- #tableau_vins_sans_ig -->
						<div id="tableau_vins_sans_ig" class="tableau_ajouts_liquidations">
							<h2>Vins sans IG</h2>
							
							<div class="recap_produit">
								<table class="tableau_recap">
									<thead>
										<tr>
											<th>Appelation</th>
											<th>Couleur</th>
											<th>Label</th>
											<th>Disponible</th>
											<th>Stock vide</th>
											<th>Pas de mouvement</th>
										</tr>
									</thead>
									<tbody>
										<?php for($i=0; $i<6; $i++) { ?>
										<tr>					     
											<td>
												<a href="#?action=supprimer_aop&id=<?php echo $i; ?>" class="supprimer">Supprimer</a>
												Côtes du Rhône
												<input type="hidden" name="aop_id" value="<?php echo $i; ?>" />
											</td>
											<td>Rouge</td>
											<td>Label</td>
											<td class="disponible">
												<input type="hidden" name="disponible" value="87">
												87HL
											</td>
											<!-- Si disponible > 0 alors disable stock vide -->
											<td class="stock_vide">
												<form method="post" action="/drm/mouvements-generaux/AOP/CVP/update/<?php echo $i; ?>" class="updateProduct">
													<input type="hidden" id="produit_<?php echo $i; ?>__csrf_token" value="6e9e93d2c2f2afcbf8cd24d7fa693113" name="produit_<?php echo $i; ?>[_csrf_token]">
													<input type="checkbox" id="produit_<?php echo $i; ?>_stock_vide" name="produit_<?php echo $i; ?>[stock_vide]">
												</form>
											</td>
											<td class="pas_mouvement">
												<form method="post" action="/drm/mouvements-generaux/AOP/CVP/update/<?php echo $i; ?>" class="updateProduct">
													<input type="hidden" id="produit_<?php echo $i; ?>__csrf_token" value="6e9e93d2c2f2afcbf8cd24d7fa693113" name="produit_<?php echo $i; ?>[_csrf_token]">
													<input type="checkbox" id="produit_<?php echo $i; ?>_pas_de_mouvement" name="produit_<?php echo $i; ?>[pas_de_mouvement]">
												</form>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<div class="btn">
									<a href="../includes/_popup_ajout_produit_VINSSANSIG.php" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_produit_VINSSANSIG" data-popup-config="configAjoutProduit">Ajouter un nouveau produit</a>
								</div>
							</div>
						</div>
						<!-- #tableau_vins_sans_ig -->
					</div>
				</div>
			
				<div id="btn_etape_dr">
					<a href="#" class="btn_prec"><span>Précédent</span></a>
					<a href="#" class="btn_suiv"><span>Suivant</span></a>
				</div>
			</div>
		</section>
		<!-- fin #principal -->
		
	</section>
	<!-- fin #contenu -->
			
<?php require('../includes/footer.php'); ?>
