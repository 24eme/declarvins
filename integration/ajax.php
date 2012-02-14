<?php 

	if($_GET)
	{
		$action = $_GET["action"];
		
		if($action == "ajout_produit")
		{

			//sleep(1);
			$type = $_GET['type'];

			$html = '<form method="post" action="../ajax.php?action=ajout_produit&type='.$type.'" id="subForm" class="popup_form">
							<input type="hidden" id="produit__csrf_token" value="121c20cd7ebc0556612b273741d2adc3" name="produit[_csrf_token]">
							<input type="hidden" value="AOP" name="certification">
							
							<div class="ligne_form">
								<label for="produit_appellation">Appellation*: </label>
								<select id="produit_appellation" name="produit[appellation]">
									<option value="CP">Côtes de Provence</option>
									<option value="CPF">Côtes de Provence Fréjus</option>
									<option value="CPSV">Côtes de Provence Sainte Victoire</option>
									<option value="CPLL">Côtes de Provence La Londe</option>
									<option value="CAP">Coteaux d\'Aix en Provence</option>
									<option value="CVP">Coteaux Varois en Provence</option>
									<option value="BAND">Bandol</option>
									<option value="CAS">Cassis</option>
									<option value="BAUX">Baux de Provence</option>
									<option value="BEL">Bellet</option>
									<option value="PAL">Palette</option>
									<option value="PIE">Coteaux de Pierrevert</option>
									<option value="CDR">Côtes du Rhône Régional</option>
									<option value="CRV">Côtes du Rhône Village</option>
									<option value="BEA">Beaumes de Venise cru</option>
									<option value="CDP">Châteauneuf du Pape</option>
									<option value="COD">Condrieu</option>
									<option value="COR">Cornas</option>
									<option value="CRO">Côte Rotie</option>
									<option value="CRH">Crozes Hermitage</option>
									<option value="GIG">Gigondas</option>
									<option value="HER">Hermitage</option>
									<option value="LIR">Lirac</option>
									<option value="SJO">Saint Joseph</option>
									<option value="SPE">Saint Peray Effervescents</option>
									<option value="SPT">Saint Peray Tranquilles</option>
									<option value="TAV">Tavel</option>
									<option value="VAC">Vacqueyras</option>
									<option value="VDB">VDN Beaumes de venise</option>
									<option value="VDR">VDN Rasteau</option>
									<option value="VBR">Vinsobres</option>
									<option value="CDN">Costières de NÃ®mes</option>
									<option value="LUB">Luberon</option>
									<option value="TRI">Côteaux du Tricastin</option>
									<option value="CVX">Ventoux</option>
									<option value="VIV">Côtes du Vivarais</option>
									<option value="CDB">Clairette de Bellegarde</option>
									<option value="BDV">Cdr Village Beaumes de Venise</option>
									<option value="CAI">Cdr Village Cairanne</option>
									<option value="CHU">Cdr Village Chusclan</option>
									<option value="LAU">Cdr Village Laudun</option>
									<option value="MAS">Cdr Village Massif d\'Uchaux</option>
									<option value="PLA">Cdr Village Plan de Dieu</option>
									<option value="PUY">Cdr Village Puymeras</option>
									<option value="RAS">Cdr Village Rasteau</option>
									<option value="ROX">Cdr Village Roaix</option>
									<option value="ROC">Cdr Village Rochegude</option>
									<option value="RLV">Cdr Village Rousset les Vignes</option>
									<option value="SAB">Cdr Village Sablet</option>
									<option value="STG">Cdr Village Saint Gervais</option>
									<option value="STM">Cdr Village Saint Maurice</option>
									<option value="SPV">Cdr Village Saint Pantaléon les Vignes</option>
									<option value="SEG">Cdr Village Séguret</option>
									<option value="SIG">Cdr Village Signargues</option>
									<option value="VAL">Cdr Village Valréas</option>
									<option value="VIN">Cdr Village Vinsobres</option>
									<option value="VIS">Cdr Village Visan</option>
								</select>
								<span class="error"></span>
							</div>
							<div class="ligne_form">
								<span class="error">
									<ul class="error_list">
										<li>Champ obligatoire</li>
									</ul>
								</span>
								<label for="produit_AOP_couleur">Couleur*: </label>
								<select id="produit_AOP_couleur" name="produit_AOP[couleur]">
									<option selected="selected" value=""></option>
									<option value="blanc">Blanc</option>
									<option value="rouge">Rouge</option>
									<option value="rose">Rosé</option>
								</select>
							</div>
							
							<div class="ligne_form">
								<label for="produit_label">Label: </label>
								<select id="produit_label" name="produit[label][]" multiple="multiple" class="select_multiple">
									<option id="produit_label_AB" value="AB">Agriculture Biologique</option>
									<option id="produit_label_AR" value="AR">Agriculture Raisonnée</option>
									<option id="produit_label_BD" value="BD">Biodynamie</option>
									<option id="produit_label_AC" value="AC">Agri confiance</option>
									<option id="produit_label_TV" value="TV">Terra Vitis</option>
									<option id="produit_label_DD" value="DD">Vigneron développement durable</option>
									<option id="produit_label_NMP" value="NMP">Nutrition Méditérannéenne en Provence</option>
									<option id="produit_label_HVE" value="HVE">Haute Valeur Environnementale</option>
									<option id="produit_label_FU" value="FU">Elevage en fût</option>
									<option id="produit_label_DO" value="DO">Domaine</option>
									<option id="produit_label_CH" value="CH">Château</option>
									<option id="produit_label_CL" value="CL">Clos</option>
									<option id="produit_label_CC" value="CC">CC">Cru Classé</option>
									<option id="produit_label_BT" value="BT">Mise en bouteille ("conditionné") à la propriété</option>
								</select>
								<span class="error"></span>
							</div>
							
							<div class="ligne_form">
								<label for="produit_label_supplementaire">Label supplémentaire: </label>
								<input type="text" id="produit_label_supplementaire" name="produit[label_supplementaire]" />
								<span class="error"></span>
							</div>
							
							<div class="ligne_form">
								<label for="produit_disponible">Disponible: </label>
								<input type="text" id="produit_disponible" name="produit[disponible]" class="num num_float" />
								<span class="error"></span>
							</div>
							
							<!--<div class="ligne_form">
								<label for="produit_stock_vide">Stock vide </label>
								<input type="checkbox" id="produit_stock_vide" name="produit[stock_vide]" />
								<span class="error"></span>
							</div>
							
							<div class="ligne_form">
								<label for="produit_pas_de_mouvement">Pas de mouvement </label>
								<input type="checkbox" id="produit_pas_de_mouvement" name="produit[pas_de_mouvement]" />
								<span class="error"></span>
							</div>-->
							
							<div class="ligne_form_btn">
								<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
								<button name="valider" class="btn_valider" type="submit">Valider</button>
							</div>
						</form>';


			
			$retour = array('success' => false, 'url' => 'http://www.google.fr', 'content' => $html);

			echo json_encode($retour);
			
		}
		
		/* Validation d'une colonne lors de la déclaration*/
		elseif($action == "valider_col")
		{
			sleep(1);
			$retour = array('id'=>'valeur');
			echo json_encode($retour);
		}
	}

?>