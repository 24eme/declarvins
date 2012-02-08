<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "DRM 2011 - MARS";
	$rub_courante = "DRM";
	$declaration_etape = 3;
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
					<li><a href="#">Côte du Rhône <span class="completion completion_validee">(3/3)</span></a></li>
					<li class="actif"><strong>AOC Ventoux <span class="completion">(3/4)</span></strong></li>
					<li><a href="#">AOC Beaumes de Venise <span class="completion">(3/4)</span></a></li>
					<li class="ajouter"><a href="#">Ajouter Appelation</a></li>
				</ul>
			
				<div id="contenu_onglet">
					
					<div class="notice">
						<h2>Raccourcis clavier :</h2>
						<ul>
							<li><kbd>Ctrl</kbd> + <kbd>Gauche</kbd> : Aller à la colonne de gauche</li>
							<li><kbd>Ctrl</kbd> + <kbd>Droite</kbd> : Aller à la colonne de droite</li>
							<li><kbd>Ctrl</kbd> + <kbd>M</kbd> : Commencer la saisie de la colonne courante</li>
							<li><kbd>Ctrl</kbd> + <kbd>Suppr</kbd> : Supprimer la colonne courante</li>
							<li><kbd>Ctrl</kbd> + <kbd>Z</kbd> : Réinitialiser les valeurs de la colonne courante</li>
							<li><kbd>Ctrl</kbd> + <kbd>Entrée</kbd> : Valider la colonne courante</li>
						</ul>
					</div>
					
					<div id="colonnes_dr">
						<!-- #colonne_intitules -->
						<div id="colonne_intitules">
							<p class="denomination">Dénomination</p>
							<p>Couleur</p>
							<p>Label</p>
							
							<div class="groupe" data-groupe-id="1">
								<p>Stock théorique principal début de mois</p>
								<ul>
									<li>Dont Vin bloqué</li>
									<li>Dont Vin warranté</li>
									<li>Dont Vin en instance</li>
								</ul>
							</div>
							
							<div class="groupe" data-groupe-id="2">
								<p>Entrées</p>
								<ul>
									<li>Achats / Récolte / Agrément IGP/ Retour</li>
									<li>Replis / Déclassement / Changement de dénomination</li>
									<li>Transfert de chai / Embouteillage</li>
								</ul>
							</div>
							
							<div class="groupe" data-groupe-id="3">
								<p>Sorties</p>
								<ul>
									<li>Vrac DAA/DAE</li>
									<li>Conditionné Export</li>
									<li>DSA / Tickets / Factures</li>
									<li>CRD France</li>
									<li>Conso Fam. / Analyses / Dégustation</li>
									<li>Pertes</li>
									<li>Non rev. / Déclassement</li>
									<li>Changement / Repli</li>
									<li>Transfert de chai / Embouteillage</li>
									<li>Lies</li>
								</ul>
							</div>
							
							<p class="stock_th_fin">Stock théorique fin de mois</p>
						</div>
						<!-- fin #colonne_intitules -->
					
						<!-- #col_saisies -->
						<div id="col_saisies">
							<script type="text/javascript">
								/* Colonne avec le focus par défaut */
								var colFocusDefaut = 1;
							</script>
						
							<div id="col_saisies_cont">
								<?php for($num_col = 1; $num_col < 5; $num_col++) { ?>
								<?php $col_id = "col_".$num_col; ?>
								
								<!-- #col_recolte_<?php echo $num_col; ?> -->
								<div id="col_recolte_<?php echo $num_col; ?>" class="col_recolte">
									<form action="../ajax.php?action=valider_col" method="post">
										<a href="#" class="col_curseur" data-curseur="<?php echo $num_col; ?>"></a>
										<h2>Nom <?php echo $num_col; ?></h2>
										<div class="col_cont">
											<p>
												<select data-val-defaut="blanc">
													<option value="blanc">blanc</option>
													<option value="rouge">rouge</option>
													<option value="rose">rosé</option>
												</select>
											</p>
											<p>
												<select data-val-defaut="biodynamie">
													<option value="biodynamie">biodynamie</option>
													<option value="label_2">label 2</option>
													<option value="label_3">label 3</option>
												</select>
											</p>
										
											<div class="groupe" data-groupe-id="1">
												<p><input type="text" value="200" data-val-defaut="200" class="num num_float somme_stock_debut" id="<?php echo $col_id; ?>-1" name="<?php echo $col_id; ?>-1" /></p>
												<ul>
													<?php for($j = 1; $j < 4; $j++) { ?>
													<li><input type="text" value="0" data-val-defaut="0" class="num num_float" id="<?php echo $col_id; ?>-1-<?php echo $j; ?>" name="<?php echo $col_id; ?>-1-<?php echo $j; ?>" /></li>
													<?php } ?>
												</ul>
											</div>
											
											<div class="groupe" data-groupe-id="2">
												<p><input type="text" value="0" data-val-defaut="0" class="num num_float somme_groupe somme_entrees" id="<?php echo $col_id; ?>-2" name="<?php echo $col_id; ?>-2" readonly="readonly" /></p>
												<ul>
													<?php for($j = 1; $j < 4; $j++) { ?>
													<li><input type="text" value="0" data-val-defaut="0" class="num num_float" id="<?php echo $col_id; ?>-2-<?php echo $j; ?>" name="<?php echo $col_id; ?>-1-<?php echo $j; ?>" /></li>
													<?php } ?>
												</ul>
											</div>
											
											<div class="groupe" data-groupe-id="3">
												<p><input type="text" value="0" data-val-defaut="0" class="num num_float somme_groupe somme_sorties" id="<?php echo $col_id; ?>-3" name="<?php echo $col_id; ?>-3" readonly="readonly" /></p>
												<ul>
													<?php for($j = 1; $j < 11; $j++) { ?>
													<li><input type="text" value="0" data-val-defaut="0" class="num num_float" id="<?php echo $col_id; ?>-1-<?php echo $j; ?>" name="<?php echo $col_id; ?>-3-<?php echo $j; ?>" /></li>
													<?php } ?>
												</ul>
											</div>
											
											<p><input type="text" value="0" data-val-defaut="0" class="num num_float somme_stock_fin" id="<?php echo $col_id; ?>-4" name="<?php echo $col_id; ?>-4" readonly="readonly" /></p>
											
											<div class="col_btn">
												<div class="btn_col_inactive">
													<button class="btn_modifier" type="submit">Modifier</button>
													<button class="btn_supprimer" type="submit">Supprimer</button>
												</div>
												<div class="btn_col_active">
													<button class="btn_reinitialiser" type="submit">Réinitialiser</button>
													<button class="btn_valider" type="submit">Valider</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<!-- fin #col_recolte_<?php echo $num_col; ?> -->
								<?php } ?>
							</div>
						</div>
						<!-- fin #col_saisies -->
					
						<button class="btn_ajouter" type="submit">Ajouter Dénomination</button>
					</div>
					
					<!--<div id="btn_colonnes_dr">
						<button class="btn_suiv" type="submit">Appelation suivante</button>
					</div>-->
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
