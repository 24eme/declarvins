<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'ajouts_liquidations', 'pourcentage' => '10')); ?>

    <section id="principal">
		<div id="application_dr">
	        <form action="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" method="post">
	        

		        <div id="btn_etape_dr">
		            <a href="<?php echo url_for('drm_informations', $drm) ?>" class="btn_prec">
		            	<span>Précédent</span>
		            </a>
	                <?php if($first_certification): ?>
		            	<button type="submit" class="btn_suiv"><span>Suivant</span></button>
	                <?php endif; ?>
		        </div>
			        
				<div id="contenu_onglet">
	        		<?php echo $form->renderHiddenFields() ?>
	        		<?php echo $form->renderGlobalErrors() ?>
					<?php if($first_certification): ?>
	                	<button class="btn_passer_etape" type="submit">Passer cette étape</button>
					<?php endif; ?>
						<p class="intro">Afin de préparer le détail de la DRM, vous pouvez préciser ici vos stocks épuisés ou l'absence de mouvements pour tout ou partie des produits.</p>
	        		
	        			<div id="ajouts_liquidations">
		        			<?php if ($drm->hasProduits()): ?>
		        			<div style="padding:15px 0 0 0; margin:0;" class="tableau_ajouts_liquidations">
			        			<table class="tableau_recap">
									<tbody>
									<tr class="alt">
										<td style="width: 694px;" colspan="2">
											<label for="<?php echo $form['droits_acquittes']->renderId() ?>">Je souhaite déclarer des produits en droits acquittés <a href="" class="msg_aide" data-msg="help_popup_mouvgen_droits_acquittes" title="Message aide"></a>
											</label>
										</td>
										<td style="width: 140px" align="center">
											<?php echo $form['droits_acquittes']->render() ?>
										</td>
									</tr>
									</tbody>
								</table>
			        		</div>
		        			<?php if(!$drm->declaration->hasMouvement()): ?>
		        			<div style="padding:0; margin:0 0 15px 0;" class="tableau_ajouts_liquidations">
			        			<table class="tableau_recap">
									<tbody>
									<tr class="alt">
										<td style="width: 694px;" colspan="2">
											<label for="<?php echo $form['pas_de_mouvement']->renderId() ?>">Pas de mouvement pour l'ensemble des produits <a href="" class="msg_aide" data-msg="help_popup_mouvgen_pasdemouvement_global" title="Message aide"></a>
											</label>
										</td>
										<td style="width: 140px" align="center">
											<?php echo $form['pas_de_mouvement']->render() ?>
										</td>
									</tr>
									</tbody>
								</table>
			        		</div>
		        			<?php endif; ?>
	        			
	        				<?php if(	$drm->declaration->hasStockEpuise()): ?>
	        				<a href="<?php echo url_for('drm_mouvements_generaux_stock_epuise', $drm) ?>" id="stock_epuise" style="float:none; margin: 0 0 15px 0;">Stock épuisé</a>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_mouvgen_stock_epuise" title="Message aide"></a>
		        			<?php endif; ?>
	        			<?php endif; ?>
	        				<?php foreach ($certifs as $certification => $val): ?>
			            <div id="tableau_<?php echo strtolower($certificationLibelle[$certification]) ?>" class="tableau_ajouts_liquidations">
			                    <h2><?php echo $certificationLibelle[$certification] ?></h2>
			                    <div class="recap_produit">
				                    <table class="tableau_recap">
				                        <thead>
                                                                <tr>
                                                                        <th style="width: 570px">Produits</th>
                                                                        <th style="width: 170px">Stock début de mois <a href="" class="msg_aide" data-msg="help_popup_mouvgen_disponible" title="Message aide"></a></th>
                                                                        <th style="width: 150px">Pas de mouvement <a href="" class="msg_aide" data-msg="help_popup_mouvgen_pasdemouvement" title="Message aide"></a></th>
                                                                </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if(isset($form[$certification])): ?>
				                        <?php foreach ($form[$certification] as $key => $embedForm): ?>
				                        	<?php include_partial('item', array('detail' => $form->getObject()->get($key), 'form' => $embedForm)) ?>
				                        <?php endforeach; ?>
				                        <?php endif; ?>
				                        </tbody>
				                    </table>
				                    <div class="btn">
                                                            <a href="<?php echo url_for('drm_mouvements_generaux_product_ajout', $drm->declaration->certifications->add($certification)) ?>" class="btn_ajouter btn_popup" data-popup="#popup_ajout_produit_<?php echo $certification ?>" data-popup-config="configForm">Ajouter un nouveau produit</a>
                                                    </div>
			                    </div>
			            </div>
		            	<?php endforeach; ?>
	            		</div>
	        		</div>

			        <div id="btn_etape_dr">
			            <a href="<?php echo url_for('drm_informations', $drm) ?>" class="btn_prec">
			            	<span>Précédent</span>
			            </a>
		                <?php if($first_certification): ?>
			            	<button type="submit" class="btn_suiv"><span>Suivant</span></button>
		                <?php endif; ?>
			        </div>

			        <div class="ligne_btn">
           				<a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
        			</div>

	      		</form>
			</div>
    </section>
</section>
