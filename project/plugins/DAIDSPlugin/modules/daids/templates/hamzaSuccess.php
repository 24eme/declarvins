<?php include_component('global', 'navTop', array('active' => 'daids')); ?>
<section id="contenu">
	<h1>Déclaration Annuelle d'Inventaire / Déclaration de Stocks</h1>
	<p id="date_drm">DAI / DS 2013</p>

	<div id="statut_declaration">

		<nav id="declaration_etapes">
			<ol>
				<li class="premier passe">
					<a href=""><span>1. Informations</span></a>
	      		</li>
	      		<li class="passe">
					<a href=""><span>2. MC / MCR</span></a>
	      		</li>
	      		<li class="actif passe">
					<a href=""><span>3. AOP</span></a>
	      		</li>
	      		<li>
					<a href=""><span>4. IGP</span></a>
	      		</li>
	      		<li>
					<a href=""><span>5. Vins sans IG</span></a>
	      		</li>
	      		<li class="dernier">
					<a href=""><span>6. Validation</span></a>
	      		</li>
	      	</ol>
		</nav>

		<div id="etat_avancement">
			<p>Vous avez saisi <strong>30<span>%</span></strong></p>
			<div id="barre_avancement">
				<div style="width: 30%"></div>
			</div>
		</div>
	</div>

	<!-- #principal -->
    <section id="principal">
		<div id="application_dr">

			<ul id="onglets_principal">
				<li class="actif">
                    <strong>Tranquiles Côtes du Rhône Régional</strong>
                </li>
                <li>
                    <a href="#">VAC</a>
                </li>
				<li class="ajouter">
					<a href="#"class="btn_popup" data-popup="#popup_ajout_appelation" data-popup-ajax="true" data-popup-config="configForm">Ajouter une appellation</a>
				</li>
				<li id="msg_aide_drm">
					<a href="" class="msg_aide" data-msg="help_popup_drm_global" data-doc="notice.pdf" title="Message aide"></a>
				</li>
			</ul>


			<div id="contenu_onglet">
            
            	<a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a>
            
                <?php include_partial('drm_recap/shortcutKeys') ?>

                <div id="colonnes_dr">
				
	            	<div id="colonne_intitules">
						<p class="couleur">Couleur</p>

						<p class="label">Labels</p>
						
						<p class="stock_th">Stock théorique au 31 Juillet - DRM <span class="unite">(HL)</span></p>

						<p>Vins de la propriété dans votre chais (<span class="unite">hl</span>)</p>
						<p>Vins logés dans votre chais pour un tiers (<span class="unite">hl</span>)</p>
						<p>Vins logés chez un tiers (<span class="unite">hl</span>)</p>
						
						<p>Total vins logés dans votre chais (<span class="unite">hl</span>)</p>

						<div class="groupe demarrage-ouvert bloque" data-groupe-id="1">
							<p>Total Stock de votre propriété (<span class="unite">hl</span>)</p>
							<ul>
								<li>Dont Réserve Bloquée</li>
								<li>Dont Vrac Vendu non retiré</li>
								<li>Dont Vrac libre à la vente</li>
								<li>Dont Conditionné</li>
								<li>Dont Vin en instance</li>
							</ul>
						</div>

						<p class="total_manq_exce">Total Manquants ou Excédents (<span class="unite">hl</span>)</p>

						<p class="stock_th">Stock mensuel théorique</p>

						<div class="groupe demarrage-ouvert bloque" data-groupe-id="2">
							<p>dont Stock moyen volume vinifié et soldé dans l'année</p>
							<ul>
								<li>Taux forfaitaire général (2.5%)</li>
								<li>Taux forfaitaire optionnel (6%)</li>
								<li>Taux forfaitaire effervescents (4%)</li>
							</ul>
						</div>

						<div class="groupe demarrage-ouvert bloque" data-groupe-id="3">
							<p>dont Stock moyen volume stocké non vinifié</p>
							<ul>
								<li>Taux forfaitaire (1%)</li>
							</ul>
						</div>

						<div class="groupe demarrage-ouvert bloque" data-groupe-id="4">
							<p>Stock moyen volume conditionné dans l'année</p>
							<ul>
								<li>Taux forfaitaire (1%)</li>
							</ul>
						</div>

						<p class="total_pertes">Total Pertes Autorisée (<span class="unite">hl</span>)</p>

						<p class="manquants_taxables">Manquants taxables éventuels (<span class="unite">hl</span>)</p>

						<p>Total Droits à payer (avant régulation)</p>
						<p>Régulation, correction ou avoir</p>

						<p class="total_droits_final">Total droits à payer</p>
					</div>


					<!-- #col_saisies -->
					<div id="col_saisies">
						<script type="text/javascript">
							/* Colonne avec le focus par défaut */
							var colFocusDefaut = 1;
						</script>

				        <div id="col_saisies_cont">
				           
				           <!-- #col_recolte_1 -->
				        	<div id="col_recolte_1" class="col_recolte col_active" data-input-focus="#drm_detail_entrees_achat" data-cssclass-rectif="">
				        		<form action="" method="post">
				        			<a href="#" class="col_curseur" data-curseur="1"></a>
			        				<h2>Rouge</h2>
			        				<div class="col_cont">
			        					<p class="label">Nom label</p>

			        					<p class="stock_th stock_th_drm">
			        						<input id="champ_1-0" type="text" value="100" class="texte stock_th stock_th_drm" readonly="readonly">
			        					</p>
			        					<p>
					                    	<input id="champ_1-1" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
					                    </p>

					                    <p>
					                    	<input id="champ_1-2" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
					                    </p>

					                    <p>
					                    	<input id="champ_1-3" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
					                    </p>

							            <p class="total_chais">
			        						<input id="champ_1-4" data-calcul="somme" data-champs="#champ_1-1;#champ_1-2" type="text" value="0" class="texte total_chais" readonly="readonly">
			        					</p>

										<div class="groupe" data-groupe-id="1">
							                <p class="total_propriete">
			        							<input id="champ_1-5" data-calcul="somme" data-champs="#champ_1-1;#champ_1-3" type="text" value="0" class="texte total_propriete" readonly="readonly">
				        					</p>
							                <ul>
							                    <li>
							                    	<input id="champ_1-6" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
							                    </li>
							                    <li>
							                    	<input id="champ_1-7" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
							                    </li>
							                    <li>
							                    	<input id="champ_1-8" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
							                    </li>
							                    <li>
							                    	<input id="champ_1-9" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
							                    </li>
							                    <li>
							                    	<input id="champ_1-10" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
							                    </li>
							                </ul>
							            </div>

							            <p class="total_manq_exce">
		        							<input id="champ_1-11" data-calcul="diff" data-champs="#champ_1-4;#champ_1-0" type="text" value="0" class="texte total_manq_exce" readonly="readonly">
			        					</p>

							            <p class="stock_th stock_th_mensuel">
		        							<input id="champ_1-12" type="text" value="0" class="texte stock_th stock_th_mensuel" readonly="readonly">
			        					</p>

										<div class="groupe" data-groupe-id="2">
											<p>
												<input id="champ_1-13" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
											</p>
											<ul class="choix_unique" data-resultat="champ_1-17">
												<li>
													<input checked="checked" id="champ_1-14-1" type="radio" value="0.025" name="stock_moy_vol_vinifie_solde" />
													<input id="champ_1-14-2" data-calcul="produit" data-champs="#champ_1-13;#champ_1-14-1" type="text" value="0" class="texte" readonly="readonly">
												</li>
												<li>
													<input id="champ_1-15-1" type="radio" value="0.06" name="stock_moy_vol_vinifie_solde" />
													<input id="champ_1-15-2" data-calcul="produit" data-champs="#champ_1-13;#champ_1-15-1" type="text" value="0" class="texte" readonly="readonly">
												</li>
												<li>
													<input id="champ_1-16-1" type="radio" value="0.04" name="stock_moy_vol_vinifie_solde" />
													<input id="champ_1-16-2" data-calcul="produit" data-champs="#champ_1-13;#champ_1-16-1" type="text" value="0" class="texte" readonly="readonly">
												</li>
											</ul>
											<input id="champ_1-17" type="hidden" value="0" >
										</div>

										<div class="groupe" data-groupe-id="3">
											<p>
												<input id="champ_1-18" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
											</p>
											<ul>
												<li>
													<input id="champ_1-19-1" type="hidden" value="0.01">
													<input id="champ_1-19-2" data-calcul="produit" data-champs="#champ_1-17;#champ_1-18-1" type="text" value="0" class="texte" readonly="readonly">
												</li>
											</ul>
										</div>

										<div class="groupe" data-groupe-id="4">
											<p>
												<input id="champ_1-20" type="text" value="0.00" data-val-defaut="0.00" class="num num_float" autocomplete="off">
											</p>
											<ul>
												<li>
													<input id="champ_1-21-1" type="hidden" value="0.01">
													<input id="champ_1-21-2" data-calcul="produit" data-champs="#champ_1-19;#champ_1-20-1" type="text" value="0" class="texte" readonly="readonly">
												</li>
											</ul>
										</div>


										<p>
											<input id="champ_1-22" data-calcul="somme" data-champs="#champ_1-16;#champ_1-18-2;champ_1-20-2" type="text" value="0" class="texte" readonly="readonly">
										</p>
										<p>
											<input id="champ_1-23" data-calcul="diff" data-champs="#champ_1-21;#champ_1-11" type="text" value="0" class="texte" readonly="readonly">
										</p>
										<p>
											<!-- Taux douane -->
											<input id="champ_1-24-1" type="hidden" value="0.05">
											<input id="champ_1-24-2" data-calcul="produit" data-champs="#champ_1-22;#champ_1-23" type="text" value="0" class="texte" readonly="readonly">
										</p>
										<p>
											<input id="champ_1-25" type="text" value="0.00" class="num num_float" autocomplete="off">
										</p>

										<p>
											<input id="champ_1-26" data-calcul="diff" data-champs="#champ_1-24-2;#champ_1-25" type="text" value="0" class="texte" readonly="readonly">
										</p>

					        			<div class="col_btn">
											<button class="btn_valider" type="submit">Valider</button>
											<button class="btn_reinitialiser" type="submit">Annuler</button>
										</div>
									</div>
								</form>
							</div>
							<!--fin #col_recolte_1 -->
				        </div>
				    </div>
				    <!-- fin #col_saisies -->
				</div>

            </div>



            <div id="btn_etape_dr">
                <a href="" class="btn_prec">
                    <span>Précédent</span>
                </a>

                <a href="" class="btn_suiv">
                    <span>Suivant</span>
                </a>
            	
            </div>

            <div class="ligne_btn" style="margin-top: 30px;">
                <a href="<?php //echo url_for('drm_delete', $drm) ?>" class="annuler_saisie btn_remise"><span>annuler la saisie</span></a>
            </div>

		</div>
	</section>
	<!-- fin #principal -->
</section>