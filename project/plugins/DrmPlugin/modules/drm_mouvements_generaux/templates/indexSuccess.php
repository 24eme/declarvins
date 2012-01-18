<script type="text/javascript">
    $(document).ready( function()
    {
        $('.supprimer').live('click', function() {
            var link = $(this);
        	$.post($(this).attr('href'), null, 
                function() {
        			link.parents('tr').remove();
        		}
        	);
        	return false;
        });
        $('#subForm').live('submit', function () {
            var id = $(this).parents('div').attr('id');
            $.post($(this).attr('action'), $(this).serializeArray(),
            	function (data) {
                	if(data.success) {
                    	document.location.href = data.url;
                	} else {
                		$('#'+id).html(data.content);
                		var linkAction = $('a[data-popup=#'+id+']');
                		$.initPopup(linkAction);
                	}
            	}, "json"
            );
            $('.addRow').show();
            return false;
        });
        $('#ajouts_liquidations :checkbox').change(function() {
            $(this).parents('form').submit();
        });
        $('.updateProduct').submit(function() {
        	$.post($(this).attr('action'), $(this).serializeArray());
        	return false;
        });
    })
</script>

<?php include_partial('global/navTop'); ?>

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
											<th>Appelation</th>
											<th>Couleur</th>
											<th>Label</th>
											<th>Disponible</th>
											<th>Stock vide</th>
											<th>Pas de mouvement</th>
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
									<a href="<?php echo url_for('drm_mouvements_generaux_product_form') ?>?certification=<?php echo $certification ?>" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_produit_<?php echo $certification ?>" data-popup-config="configAjoutProduit">Ajouter un nouveau produit</a>
								</div>
		                    	<!-- <a href="<?php echo url_for('drm_mouvements_generaux_product_form') ?>" class="showForm " id="<?php echo $certification ?>" style="display: inline-block;width:100%;text-align:right;">Ajouter un nouveau produit</a> -->
		                    </div>
		            </div>
		            <?php endforeach; ?>
            	</div>
        	</div>
	        <div id="btn_etape_dr">
	            <a href="<?php echo url_for('@drm_informations') ?>" class="btn_prec">Précédent</a>
	            <a id="nextStep" href="<?php echo url_for('drm_recap', ConfigurationClient::getCurrent()->declaration->certifications->AOP) ?>" class="btn_suiv">Suivant</a>
	        </div>
		</div>
    </section>
</section>