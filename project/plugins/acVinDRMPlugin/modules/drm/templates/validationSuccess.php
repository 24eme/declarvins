<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
        <form id="formValidation" action="<?php echo url_for('drm_validation', $drm) ?>" method="post">
            <?php echo $form->renderGlobalErrors() ?>
            <?php echo $form->renderHiddenFields() ?>
            
            <div id="btn_etape_dr">
            	<?php if (!$drm->isIncomplete()): ?>
                <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_vrac', array("sf_subject" => $drm, "precedent" => true))?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if (!$drmValidation->hasErrors()): ?>
                <button type="submit" class="btn_suiv"<?php if ($drmValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>>
                    <span>Valider</span>
                </button>
                <?php endif; ?>
            </div>
            
            <div id="application_dr">

                <div id="validation_intro">
                    <h2>Validation</h2>
                    <p>Vous êtes sur le point de valider votre DRM. Merci de vérifier vos données.</p>
                </div>
                
                <?php if ($drmValidation->hasErrors() || $drmValidation->hasWarnings()) { ?>
                <div id="contenu_onglet">
               
                    <div id="retours">
                        <?php
                        if ($drmValidation->hasErrors()) {
                            include_partial('erreurs', array('drm' => $drm, 'drmValidation' => $drmValidation));
                        }
                        if ($drmValidation->hasWarnings()) {
                            include_partial('vigilances', array('drm' => $drm, 'drmValidation' => $drmValidation));
                        }
                        ?>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (count($engagements) > 0) { ?>
                <div id="contenu_onglet" class="tableau_ajouts_liquidations">
                    <?php include_partial('engagements', array('drm' => $drm, 'drmValidation' => $drmValidation, 'engagements' => $engagements, 'form' => $form)); ?>                    
                </div>
                <?php } ?>
                
                <div id="contenu_onglet">
                    <?php if($drm->declaration->hasMouvement() && !$drm->declaration->hasStockEpuise()):  ?>
                        <?php include_partial('drm/recap', array('drm' => $drm)) ?>
                    <?php else: ?>
                        <?php include_partial('drm/pasDeMouvement', array('drm' => $drm)) ?>
                    <?php endif; ?>
                </div>
                <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <div style="background: #E3E2E2; color: #3E3E3E; border-radius: 5px; margin-bottom: 25px;">
                    <div style="padding: 4px 0 10px 10px;">
                    	<div style="padding: 10px 0px; font-weight: bold; display: block;">DRM incomplète</div>
                    	<div>
	                        <?php echo $form['manquants']['igp']->renderError() ?>
	                        <?php echo $form['manquants']['igp']->render() ?>
	                        <?php echo $form['manquants']['igp']->renderLabel() ?>
                        </div>
                    	<div>
	                        <?php echo $form['manquants']['contrats']->renderError() ?>
	                        <?php echo $form['manquants']['contrats']->render() ?>
	                        <?php echo $form['manquants']['contrats']->renderLabel() ?>
                        </div>
                    </div>
                </div>
				<?php endif; ?>
                <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <?php if ($form->getObject()->exist('observations') && $form->getObject()->observations): ?>
                <div style="background: #E3E2E2; color: #3E3E3E; border-radius: 5px; margin-bottom: 25px;">
                    <div style="padding: 4px 10px 10px 10px;">
                		<label style="padding: 10px 0px; font-weight: bold; display: block;">Observations</label>
                		<pre style="background: #fff; padding: 8px 0;"><?php echo $form->getObject()->observations ?></pre>
            		</div>
                </div>
                <?php endif; ?>
                <div style="background: #E3E2E2; color: #3E3E3E; border-radius: 5px; margin-bottom: 25px;">
                    <div style="padding: 4px 0 10px 10px;">
                        <?php echo $form['commentaires']->renderError() ?>
                        <?php echo $form['commentaires']->renderLabel(null, array("style" => "padding: 10px 0px; font-weight: bold; display: block;")) ?>
                        <?php echo $form['commentaires']->render(array("style" => "width: 872px; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                    </div>
                </div>
                <?php else: ?>
                <div style="background: #E3E2E2; color: #3E3E3E; border-radius: 5px; margin-bottom: 25px;">
                    <div style="padding: 4px 0 10px 10px;">
                        <?php echo $form['observations']->renderError() ?>
                        <?php echo $form['observations']->renderLabel(null, array("style" => "padding: 10px 0px; font-weight: bold; display: block;")) ?>
                        <?php echo $form['observations']->render(array("style" => "width: 872px; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <a id="telecharger_pdf" href="<?php echo url_for('drm_pdf', $drm) ?>">Visualisez le brouillon de DRM en PDF</a>
            
            <div id="btn_etape_dr">
            	<?php if (!$drm->isIncomplete()): ?>
                <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_vrac', array("sf_subject" => $drm, "precedent" => true))?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (!$drmValidation->hasErrors()): ?>
                <button type="submit" class="btn_suiv"<?php if ($drmValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>>
                    <span>Valider</span>
                </button>
                <?php endif; ?>
            </div>

            <div class="ligne_btn">
            	<?php if (!$drm->isIncomplete()): ?>
                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
                <?php endif; ?>
            </div>

        </form>
    </section>
</section>
<script type="text/javascript">
	$(document).ready(function () {
		$("#formValidation").submit(function(){
			return confirm("Une fois votre déclaration validée, vous ne pourrez plus la modifier.\n\nConfirmez vous la validation de votre DRM ?");
		});
	});
</script>
