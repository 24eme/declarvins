<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'ajouts-liquidations', 'pourcentage' => '10')); ?>

    <section id="principal">
		<div id="application_dr">
			<ul id="onglets_principal"><li class="actif"><strong>Mouvements Généraux</strong></li></ul>
			<div id="contenu_onglet">
				<a href="<?php echo url_for('drm_recap', ConfigurationClient::getCurrent()->declaration->certifications->AOP) ?>" class="btn_passer_etape">Passer cette étape</a>	
				<p class="intro">Au cours du mois écoulé, avez-vous connu des changements de structure particuliers ?</p>
	        	<?php if ($sf_user->hasFlash('notice')): ?>
	        		<p><?php echo $sf_user->getFlash('notice') ?></p>
	        	<?php endif; ?>
	        	
        		<div id="form" style="padding:10px 0;">
        	
        		</div>
        		
        		<div id="ajouts_liquidations">
        			<?php foreach ($forms as $certification => $tabForm): ?>
		            <div id="tableau_<?php echo strtolower($certificationLibelle[$certification]) ?>" class="tableau_ajouts_liquidations">
		                    <h2><?php echo $certificationLibelle[$certification] ?></h2>
		                    <div class="recap_produit">
			                    <table class="tableau_recap">
			                        <thead>
										<tr>
											<th style="width: 570px">Produits</th>
											<th>Disponible</th>
											<th>Rien à déclarer</th>
										</tr>
									</thead>
									<tbody>
			                        <?php
			                        if ($tabForm):
			                            foreach ($tabForm as $form):
			                                ?>
			                                <?php include_partial('produitLigneModificationForm', array('form' => $form)) ?>
			                                <?php
			                            endforeach;
			                        endif;
			                        ?>
			                        </tbody>
			                    </table>
			                    <div class="btn">
									<a href="<?php echo url_for(array('sf_route' => 'drm_mouvements_generaux_product_form', 'certification' => $certification)) ?>" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_produit_<?php echo $certification ?>" data-popup-config="configAjoutProduit">Ajouter un nouveau produit</a>
								</div>
		                    </div>
		            </div>
		            <?php endforeach; ?>
            	</div>
        	</div>
	        <div id="btn_etape_dr">
	            <a href="<?php echo url_for('@drm_informations') ?>" class="btn_prec">Précédent</a>
                <?php if($first_certification): ?>
	            <a id="nextStep" href="<?php echo url_for('drm_recap', $first_certification->getConfig()) ?>" class="btn_suiv">Suivant</a>
                <?php endif; ?>
	        </div>
		</div>
    </section>
</section>