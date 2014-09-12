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
                <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_vrac', array("sf_subject" => $drm, "precedent" => true))?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <button type="submit" class="btn_suiv"<?php if ($drmValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>>
                    <span>Valider</span>
                </button>
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

                <div style="background: #E3E2E2; color: #3E3E3E; border-radius: 5px; margin-bottom: 25px;">
                    <div style="padding: 4px 0 10px 10px;">
                        <?php echo $form['commentaires']->renderError() ?>
                        <?php echo $form['commentaires']->renderLabel(null, array("style" => "padding: 10px 0px; font-weight: bold; display: block;")) ?>
                        <?php echo $form['commentaires']->render(array("style" => "width: 872px; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                    </div>
                </div>
            </div>
            <a id="telecharger_pdf" href="<?php echo url_for('drm_pdf', $drm) ?>">Visualisez le brouillon de DRM en PDF</a>
            
            <div id="btn_etape_dr">
                <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_vrac', array("sf_subject" => $drm, "precedent" => true))?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <button type="submit" class="btn_suiv"<?php if ($drmValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>>
                    <span>Valider</span>
                </button>
            </div>

            <div class="ligne_btn">
                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
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
