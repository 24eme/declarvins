<?php use_helper('Float'); ?>
<?php include_partial('global/navTop', array('active' => 'drm')); ?>


<section id="contenu">

    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
    	<form action="<?php echo url_for('@drm_validation') ?>" method="post">
    		<?php echo $form->renderGlobalErrors() ?>
			<?php echo $form->renderHiddenFields() ?>
			<div id="application_dr">
	            <div id="contenu_onglet">
	            
	            	<div id="retours">
	            		<?php if ($drmValidation->hasErrors()): ?>
	            			<h3>Points bloquants</h3>
	            			<?php foreach ($drmValidation->getErrors() as $error): ?>
	            				<?php echo $error->getRawValue() ?><br />
	            			<?php endforeach; ?>
	            			<br />
	            		<?php endif; ?>
	            		<?php if ($drmValidation->hasWarnings()): ?>
	            			<h3>Points de vigilance</h3>
	            			<?php foreach ($drmValidation->getWarnings() as $warning): ?>
	            				<?php echo $warning->getRawValue() ?><br />
	            			<?php endforeach; ?>
	            			<br />
	            		<?php endif; ?>
	            		<?php if ($drmValidation->hasEngagements()): ?>
	            			<h3>Engagements</h3>
	            			<?php foreach ($drmValidation->getEngagements() as $engagement): ?>
	            				<div class="ligne_form">
									<?php echo $form['engagement_'.$engagement->getCode()]->renderLabel() ?>
									<?php echo $form['engagement_'.$engagement->getCode()]->render() ?>
									<span class="error"><?php echo $form['engagement_'.$engagement->getCode()]->renderError() ?></span>
								</div>
	            			<?php endforeach; ?>
	            		<?php endif; ?>
	            	</div>
	            	
	                <div id="tableau_aop" class="tableau_ajouts_liquidations">
	                <?php foreach($drm->declaration->certifications as $certification): ?>
	                    <h2><?php echo $certification->getConfig()->libelle ?></h2>
	                    <table class="tableau_recap">
	                        <thead>
	                            <tr>
	                                <td style="border: none;">&nbsp;</td>
	                                <?php foreach($certification->appellations as $appellation): ?>
	                                    <th><?php echo $appellation->getConfig()->libelle ?></th>
	                                <?php endforeach; ?>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            <tr class="alt">
	                                <th style="font-weight: bold; border: none;">Stock début de mois</th>
	                                <?php foreach($certification->appellations as $appellation): ?>
	                                    <td><?php echoFloat($appellation->total_debut_mois) ?></td>
	                                <?php endforeach; ?>
	                            </tr>
	                            <tr>
	                                <th style="font-weight: bold; border: none;">Entrées</th>
	                                <?php foreach($certification->appellations as $appellation): ?>
	                                    <td><?php echoFloat($appellation->total_entrees) ?></td>
	                                <?php endforeach; ?>
	                            </tr>
	                            <tr>
	                                <th style="font-weight: bold; border: none;">Sorties</th>
	                                <?php foreach($certification->appellations as $appellation): ?>
	                                    <td><?php echoFloat($appellation->total_sorties) ?></td>
	                                <?php endforeach; ?>
	                            </tr>
	                            <tr class="alt">
	                                <th style="font-weight: bold; border: none;"><strong>Stock fin de mois</strong></th>
	                                <?php foreach($certification->appellations as $appellation): ?>
	                                    <td><strong><?php echoFloat($appellation->total) ?></strong></td>
	                                <?php endforeach; ?>
	                            </tr>
	                        </tbody>
	                    </table>
	                <?php endforeach; ?>
	                </div>
	                
	            </div>
		    </div>
	        <div id="btn_etape_dr">
				<a href="<?php echo url_for('drm_vrac') ?>" class="btn_prec">Précédent</a>
	            <button name="valider" class="btn_suiv" type="submit">Valider</button>
			</div>
		</form>
    </section>
</section>
