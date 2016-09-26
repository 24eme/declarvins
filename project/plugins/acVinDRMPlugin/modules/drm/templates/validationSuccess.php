
<?php use_helper('Link'); ?>
<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
    	<?php if ($drmCiel->hasErreurs()): ?>
    	<div class="error_list" style="margin-bottom: 20px;">
		    <h3 style="margin-bottom: 15px;">Erreurs lors de la transmission CIEL (veuillez corriger votre DRM ou contacter votre interprofession pour plus d'information sur les erreurs rencontrées)&nbsp;:</h3>
		    <ol style="font-weight: normal;">
		        <?php foreach ($drmCiel->getErreurs() as $erreur): ?>
		            <li><?php echo $erreur ?></li>
		        <?php endforeach; ?>
		    </ol>
		</div>
    	<?php endif; ?>
        <form id="formValidation" action="<?php echo ($sf_user->getCompte()->exist('dematerialise_ciel') && $sf_user->getCompte()->dematerialise_ciel)? url_for('drm_transfer_ciel', $drm) :  url_for('drm_validation', $drm); ?>" method="post">
            <?php echo $form->renderGlobalErrors() ?>
            <?php echo $form->renderHiddenFields() ?>
            
            <div id="btn_etape_dr">
            	<?php if (!$drm->isIncomplete()): ?>
                <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_vrac', array('sf_subject' => $drm, 'precedent' => '1')) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if (!$drmValidation->hasErrors()): ?>
                <?php if(($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$etablissement->getCompteObject()) || ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement->getCompteObject() && !$etablissement->getCompteObject()->dematerialise_ciel) || !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <button type="submit" class="btn_suiv">
                    <span>Valider<?php if(!$drm->hasVersion() && $sf_user->getCompte()->exist('dematerialise_ciel') && $sf_user->getCompte()->dematerialise_ciel): ?> et envoyer à CIEL<?php endif; ?></span>
                </button>
                <?php endif; ?>
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
                    <?php if(($drm->declaration->hasMouvement() && !$drm->declaration->hasStockEpuise()) || $drm->hasMouvementsCrd()):  ?>
                        <?php include_partial('drm/recap', array('drm' => $drm)) ?>
                		<?php include_partial('drm/droits', array('drm' => $drm, 'circulation' => $droits_circulation, 'hide_cvo' => true)) ?>
                		<?php if($drm->droits->douane->getReport() > 0 && $drm->get('declaratif')->get('paiement')->get('douane')->isAnnuelle()): ?>
                		<div style="height: 20px;">
                			<a href="<?php echo url_for('drm_payer_report', $drm) ?>" onclick="return confirm('Etes vous sûre de vouloir remettre le report à zéro ?');" style="text-transform: uppercase; color: #FFFFFF; height: 19px; line-height: 20px; font-weight: bold; padding: 0 10px; background-color: #ff9f00; border: 1px solid #ECEBEB; float: right;">J'ai payé le cumul des droits de circulation et souhaite remettre le report à zéro</a>
                		</div>
                		<?php endif; ?>
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
                <?php endif; ?>
            </div>
            <a id="telecharger_pdf" href="<?php echo url_for('drm_pdf', $drm) ?>">Visualisez le brouillon de DRM</a>
            <a id="telecharger_pdf" style="margin-left: 225px; padding-left: 5px; background: #9e9e9e;" target="_blank" href="<?php echo link_to_edi('testDRMEdi', array('id_drm' => $drm->_id, 'format' => 'xml')); ?>">Visualisez le XML CIEL</a>
            
            <div id="btn_etape_dr">
            	<?php if (!$drm->isIncomplete()): ?>
                <?php if ($drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <a href="<?php echo url_for('drm_vrac', array('sf_subject' => $drm, 'precedent' => '1')) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (!$drmValidation->hasErrors()): ?>
                <?php if(($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$etablissement->getCompteObject()) || ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement->getCompteObject() && !$etablissement->getCompteObject()->dematerialise_ciel) || !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <button type="submit" class="btn_suiv">
                    <span>Valider<?php if($sf_user->getCompte()->exist('dematerialise_ciel') && $sf_user->getCompte()->dematerialise_ciel): ?> et envoyer à CIEL<?php endif; ?></span>
                </button>
                <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="ligne_btn">
            	<?php if (!$drm->isIncomplete()): ?>
                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
                <?php endif; ?>
                <button id="brouillon" style="text-transform: uppercase; color: #FFFFFF; height: 25px; line-height: 22px; font-weight: bold; padding: 0 10px; background-color: #989898; border: 1px solid  #ECEBEB; float: right;" type="submit"><span>Sauvegarder le brouillon</span></button>
            </div>

        </form>
    </section>
</section>
<script type="text/javascript">
	$(document).ready(function () {
		$("#formValidation").submit(function(){
			if ($('#<?php echo $form['brouillon']->renderId() ?>').val() == 1) {
				return true;
			} else { 
				return confirm("Une fois votre déclaration validée, vous ne pourrez plus la modifier.\n\nConfirmez vous la validation de votre DRM ?");
			}
		});
		$("#brouillon").click(function() {
			$('#<?php echo $form['brouillon']->renderId() ?>').val(1);
			$("#formValidation").submit();
			return false;
		});
	});
</script>
