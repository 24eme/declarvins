<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "DRM 2011 - MARS";
	$rub_courante = "DRM";
	$declaration_etape = 2;
	$declaration_avancement = 30;
	$css_spec = "declaration.less";
	$js_spec = "drm.js;declaration.js";
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
								<form action="../ajax.php?action=modifier_produit" method="post">
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
											<!-- garder cette ligne -->
											<tr class="vide">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<!-- fin garder cette ligne -->
											<?php for($i=0; $i<6; $i++) { ?>
											<tr>					     
												<td>
													Côtes du Rhône
													<input type="hidden" name="aop_id" value="<?php echo $i; ?>" />
												</td>
												<td>Rouge</td>
												<td>Label</td>
												<td class="disponible">
													<input type="hidden" name="disponible" value="87" />
													87HL
												</td>
												<!-- Si disponible > 0 alors disable stock vide -->
												<td class="stock_vide"><input type="checkbox" name="tab_1-<?php echo $i; ?>-1" disabled="disabled" /></td>
												<td class="pas_mouvement"><input type="checkbox" name="tab_1-<?php echo $i; ?>-2" /></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</form>
							</div>
							
							<div class="ajout_produit">
								<form action="../ajax.php?action=ajouter_produit" method="post">
									<div class="btn">
										<a href="#" class="btn_ajouter">Ajouter un nouveau produit</a>
									</div>
								
									<table class="tableau_ajout">
										<tbody>
											<tr>					     
												<td>
													<select name="appelation">
														<option value="CP">Côtes de Provence</option>
														<option value="CPF">Côtes de Provence Fréjus</option>
														<option value="CPSV">Côtes de Provence Sainte Victoire</option>
														<option value="CPLL">Côtes de Provence La Londe</option>
														<option value="CAP">Coteaux d'Aix en Provence </option>
														<option value="CVP">Coteaux Varois en Provence </option>
													</select>
												</td>
												<td>
													<select name="couleur">
														<option value="Rouge">Rouge</option>
														<option value="Rosé">Rosé</option>
														<option value="Blanc">Blanc</option>
													</select>
												</td>
												<td>
													<select name="label">
														<option value="AB">AB</option>
														<option value="AR">AR</option>
														<option value="BD">BD</option>
														<option value="AC">AC</option>
														<option value="TV">TV</option>
														<option value="DD">DD</option>
														<option value="NMP">NMP</option>
														<option value="HVE">HVE</option>
													</select>
												</td>
												<td class="disponible"><input type="text" class="num num_float" name="disponible" /> HL</td>
												<td class="stock_vide"><input type="checkbox" name="stock_vide" /></td>
												<td class="pas_mouvement"><input type="checkbox" name="pas_mouvement" /></td>
											</tr>
										</tbody>
									</table>
									
									<div class="btn">
										<button name="annuler" class="btn_annuler">Annuler</button>
										<button name="valider" class="btn_valider">Valider</button>
									</div>
								</form>
							</div>
						</div>
						<!-- #tableau_aop -->
						
						<!-- #tableau_igp -->
						<div id="tableau_igp" class="tableau_ajouts_liquidations">
							<h2>IGP</h2>
							
							<div class="recap_produit">
								<form action="../ajax.php?action=modifier_produit" method="post">
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
											<!-- garder cette ligne -->
											<tr class="vide">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<!-- fin garder cette ligne -->
											<?php for($i=0; $i<6; $i++) { ?>
											<tr>					     
												<td>
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
												<td class="stock_vide"><input type="checkbox" name="tab_1-<?php echo $i; ?>-1" disabled="disabled" /></td>
												<td class="pas_mouvement"><input type="checkbox" name="tab_2-<?php echo $i; ?>-2" /></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</form>
							</div>
							
							<div class="ajout_produit">
								<form action="../ajax.php?action=ajouter_produit" method="post">
									<div class="btn">
										<a href="#" class="btn_ajouter">Ajouter un nouveau produit</a>
									</div>
								
									<table class="tableau_ajout">
										<tbody>
											<tr>					     
												<td>
													<select name="appelation">
														<option value="CP">Côtes de Provence</option>
														<option value="CPF">Côtes de Provence Fréjus</option>
														<option value="CPSV">Côtes de Provence Sainte Victoire</option>
														<option value="CPLL">Côtes de Provence La Londe</option>
														<option value="CAP">Coteaux d'Aix en Provence </option>
														<option value="CVP">Coteaux Varois en Provence </option>
													</select>
												</td>
												<td>
													<select name="couleur">
														<option value="Rouge">Rouge</option>
														<option value="Rosé">Rosé</option>
														<option value="Blanc">Blanc</option>
													</select>
												</td>
												<td>
													<select name="label">
														<option value="AB">AB</option>
														<option value="AR">AR</option>
														<option value="BD">BD</option>
														<option value="AC">AC</option>
														<option value="TV">TV</option>
														<option value="DD">DD</option>
														<option value="NMP">NMP</option>
														<option value="HVE">HVE</option>
													</select>
												</td>
												<td class="disponible"><input type="text" class="num num_float" name="disponible" /> HL</td>
												<td class="stock_vide"><input type="checkbox" name="stock_vide" /></td>
												<td class="pas_mouvement"><input type="checkbox" name="pas_mouvement" /></td>
											</tr>
										</tbody>
									</table>
									
									<div class="btn">
										<button name="annuler" class="btn_annuler">Annuler</button>
										<button name="ajouter" class="btn_valider">Valider</button>
									</div>
								</form>
							</div>
						</div>
						<!-- #tableau_igp -->
						
						<!-- #tableau_vins_sans_ig -->
						<div id="tableau_vins_sans_ig" class="tableau_ajouts_liquidations">
							<h2>Vins sans IG</h2>
							
							<div class="recap_produit">
								<form action="../ajax.php?action=modifier_produit" method="post">
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
										<!-- garder cette ligne -->
										<tr class="vide">
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<!-- fin garder cette ligne -->
										<?php for($i=0; $i<6; $i++) { ?>
										<tr>					     
											<td>
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
											<td class="stock_vide"><input type="checkbox" name="tab_1-<?php echo $i; ?>-1" disabled="disabled" /></td>
											<td class="pas_mouvement"><input type="checkbox" name="tab_3-<?php echo $i; ?>-2" /></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								</form>
							</div>
							
							<div class="ajout_produit">
								<form action="../ajax.php?action=ajouter_produit" method="post">
									<div class="btn">
										<a href="#" class="btn_ajouter">Ajouter un nouveau produit</a>
									</div>
								
									<table class="tableau_ajout">
										<tbody>
											<tr>					     
												<td>
													<select name="appelation">
														<option value="CP">Côtes de Provence</option>
														<option value="CPF">Côtes de Provence Fréjus</option>
														<option value="CPSV">Côtes de Provence Sainte Victoire</option>
														<option value="CPLL">Côtes de Provence La Londe</option>
														<option value="CAP">Coteaux d'Aix en Provence </option>
														<option value="CVP">Coteaux Varois en Provence </option>
													</select>
												</td>
												<td>
													<select name="couleur">
														<option value="Rouge">Rouge</option>
														<option value="Rosé">Rosé</option>
														<option value="Blanc">Blanc</option>
													</select>
												</td>
												<td>
													<select name="label">
														<option value="AB">AB</option>
														<option value="AR">AR</option>
														<option value="BD">BD</option>
														<option value="AC">AC</option>
														<option value="TV">TV</option>
														<option value="DD">DD</option>
														<option value="NMP">NMP</option>
														<option value="HVE">HVE</option>
													</select>
												</td>
												<td class="disponible"><input type="text" class="num num_float" name="disponible" /> HL</td>
												<td class="stock_vide"><input type="checkbox" name="stock_vide" /></td>
												<td class="pas_mouvement"><input type="checkbox" name="pas_mouvement" /></td>
											</tr>
										</tbody>
									</table>
									
									<div class="btn">
										<button name="annuler" class="btn_annuler">Annuler</button>
										<button name="ajouter" class="btn_valider">Valider</button>
									</div>
								</form>
							</div>
						</div>
						<!-- #tableau_vins_sans_ig -->
					</div>
				</div>
			
				<div id="btn_etape_dr">
					<a href="#" class="btn_prec">Précédent</a>
					<a href="#" class="btn_suiv">Suivant</a>
				</div>
			</div>
		</section>
		<!-- fin #principal -->
	
	</section>
	<!-- fin #contenu -->
		
<?php require('../includes/footer.php'); ?>
