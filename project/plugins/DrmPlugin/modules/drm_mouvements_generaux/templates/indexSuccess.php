<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'ajouts_liquidations', 'pourcentage' => '10')); ?>

    <section id="principal">
		<div id="application_dr">
			<ul id="onglets_principal"><li class="actif"><strong>Mouvements Généraux</strong></li></ul>
			<div id="contenu_onglet">
				<?php if($first_certification): ?>
				<a href="<?php echo url_for('drm_recap', $first_certification) ?>" class="btn_passer_etape">Passer cette étape</a>	
				<?php endif; ?>
				<p class="intro">Au cours du mois écoulé, avez-vous connu des changements de structure particuliers ?</p>
	        	
        		<div id="form" style="padding:10px 0;">
        			<input id="produits_pas_de_mouvement" type="checkbox" value="" />
        			<label for="produits_pas_de_mouvement">Pas de mouvement pour l'ensemble des produits</label>
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
											<th>Pas de mouvement</th>
										</tr>
									</thead>
									<tbody>
			                        <?php
			                        if ($tabForm):
			                            foreach ($tabForm as $form):
			                                ?>
			                                <?php include_partial('item', array('form' => $form)) ?>
			                                <?php
			                            endforeach;
			                        endif;
			                        ?>
			                        </tbody>
			                    </table>
			                    <div class="btn">
									<a href="<?php echo url_for(array('sf_route' => 'drm_mouvements_generaux_product_form', 
																	  'sf_subject' => $drm, 
																	  'certification' => $certification)) ?>" class="btn_ajouter btn_popup" data-popup="#popup_ajout_produit_<?php echo $certification ?>" data-popup-config="configForm">Ajouter un nouveau produit</a>
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
	            <form action="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" method="post">
	            	<button type="submit" class="btn_suiv"><span>Suivant</span></button>
	            </form>
                <?php endif; ?>
	        </div>
		</div>
    </section>
</section>

<script type="text/javascript">
	$(document).ready(function () {
		$("#produits_pas_de_mouvement").click(function() {
			if ($("#produits_pas_de_mouvement:checked").length > 0) {
				$("#ajouts_liquidations input.pas_de_mouvement[type=checkbox]:not(:checked)").attr('checked', 'checked');
			}
		});

		$("#ajouts_liquidations input.pas_de_mouvement[type=checkbox]").click(function() {
			if ($("#ajouts_liquidations input.pas_de_mouvement[type=checkbox]:checked").length == $("#ajouts_liquidations input.pas_de_mouvement[type=checkbox]").length) {
				$("#produits_pas_de_mouvement").attr('checked', 'checked')
			} else {
				$("#produits_pas_de_mouvement").removeAttr('checked');
			}
		});
	});
</script>