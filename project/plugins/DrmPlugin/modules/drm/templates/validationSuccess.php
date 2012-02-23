<?php use_helper('Float'); ?>
<?php include_partial('global/navTop', array('active' => 'drm')); ?>


<section id="contenu">

    <?php include_partial('drm/header'); ?>
    <?php include_component('drm', 'etapes', array('etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
    	<form action="<?php echo url_for('@drm_validation') ?>" method="post">
	        <?php echo $form->renderGlobalErrors() ?>
			<?php echo $form->renderHiddenFields() ?>
			<div id="application_dr">
	            <div id="contenu_onglet">
	            	<div id="retours">
	            		<?php 
	            			if ($drmValidation->hasErrors()) {
	            				include_partial('erreurs', array('drmValidation' => $drmValidation));
	            			}
	            		?>
	            		<?php 
	            			if ($drmValidation->hasWarnings()) {
	            				include_partial('vigilances', array('drmValidation' => $drmValidation));
	            			}
	            		?>
	            		<?php 
	            			if ($drmValidation->hasEngagements()) {
	            				include_partial('engagements', array('drmValidation' => $drmValidation, 'form' => $form));
	            			}
	            		?>
	            	</div>
	           	</div>
	           	<div id="contenu_onglet">
	                <div id="tableau_aop" class="tableau_ajouts_liquidations">
	                <?php foreach($drm->declaration->certifications as $certification): ?>
	                    <h2><?php echo $certification->getConfig()->libelle ?></h2>
	                    <table class="tableau_recap">
	                        <thead>
	                            <tr>
	                                <td style="border: none;">&nbsp;</td>
	                                <th style="font-weight: bold; border: none;">Stock début de mois</th>
	                                <th style="font-weight: bold; border: none;">Entrées</th>
	                                <th style="font-weight: bold; border: none;">Sorties</th>
	                                <th style="font-weight: bold; border: none;"><strong>Stock fin de mois</strong></th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	<?php foreach($certification->appellations as $appellation): ?>
	                        		<tr class="alt">
                                    	<td><?php echo $appellation->getConfig()->libelle ?></td>
                                    	<td><strong><?php echoFloat($appellation->total_debut_mois) ?></strong></td>
                                    	<td><?php echoFloat($appellation->total_entrees) ?></td>
                                    	<td><?php echoFloat($appellation->total_sorties) ?></td>
                                    	<td><strong><?php echoFloat($appellation->total) ?></strong></td>
                           	 		</tr>
	                        	<?php endforeach; ?>
	                        </tbody>
	                    </table>
	                <?php endforeach; ?>
	                </div>
	                
	            </div>
		    </div>
	        <div id="btn_etape_dr">
				<a href="<?php echo url_for('drm_vrac') ?>" class="btn_prec">
					<span>Précédent</span>
				</a>
				<button type="submit" class="btn_suiv"<?php if ($drmValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>><span>Valider</span></button>
			</div>
		</form>
    </section>
</section>
