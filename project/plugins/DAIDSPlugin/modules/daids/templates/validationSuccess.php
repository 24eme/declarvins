<?php include_component('global', 'navTop', array('active' => 'daids')); ?>

<section id="contenu">

    <?php include_partial('daids/header', array('daids' => $daids)); ?>
    <?php include_component('daids', 'etapes', array('daids' => $daids, 'etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
        <form id="formValidation" action="<?php echo url_for('daids_validation', $daids) ?>" method="post">
            <?php echo $form->renderGlobalErrors() ?>
            <?php echo $form->renderHiddenFields() ?>
            
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('daids_recap_redirect', $daids) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <button type="submit" class="btn_suiv"<?php if ($daidsValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>><span>
                    Valider
                    <?php if ($daids->needNextVersion() && $daids->getSuivante()): ?>
                        <small style="font-size: 10px;">et réctifier la DAI/DS suivante</small>
                    <?php endif; ?>
                    </span>
                </button>
            </div>
            
            <div id="application_dr">
               
                <div id="validation_intro">
                    <h2>Validation</h2>
                    <p>Vous êtes sur le point de valider votre DAI/DS. Merci de vérifier vos données.</p>
                </div>
                
                <?php if ($daidsValidation->hasErrors() || $daidsValidation->hasWarnings()): ?>
                <div id="contenu_onglet">
                    <div id="retours">
                        <?php
                        if ($daidsValidation->hasErrors()) {
                            include_partial('erreurs', array('daids' => $daids, 'daidsValidation' => $daidsValidation));
                        }
                        if ($daidsValidation->hasWarnings()) {
                            include_partial('vigilances', array('daids' => $daids, 'daidsValidation' => $daidsValidation));
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($daidsValidation->hasEngagements()): ?>
                <div id="contenu_onglet" class="tableau_ajouts_liquidations">
                    <?php include_partial('engagements', array('daids' => $daids, 'daidsValidation' => $daidsValidation, 'form' => $form)); ?>                    
                </div>
                <?php endif; ?>
                
                <div id="contenu_onglet">
                	<?php include_partial('daids/recap', array('daids' => $daids, 'etablissement' => $etablissement)) ?>
                </div>
            </div>
            <a id="telecharger_pdf" href="<?php echo url_for('daids_pdf', $daids) ?>">Visualisez le brouillon de DAI/DS en PDF</a>
            
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('daids_recap_redirect', $daids) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <button type="submit" class="btn_suiv"<?php if ($daidsValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>><span>
                    Valider
                    <?php if ($daids->needNextVersion() && $daids->getSuivante()): ?>
                        <small style="font-size: 10px;">et réctifier la DAI/DS suivante</small>
                    <?php endif; ?>
                    </span>
                </button>
            </div>

            <div class="ligne_btn" style="margin-top: 30px;">
                <a href="<?php echo url_for('daids_delete_one', $daids) ?>" class="annuler_saisie btn_remise"><span>annuler la saisie</span></a>
            </div>

        </form>
    </section>
</section>
<script type="text/javascript">
	$(document).ready(function () {
		$("#formValidation").submit(function(){
			return confirm("Une fois votre déclaration validée, vous ne pourrez plus la modifier.\n\nConfirmez vous la validation de votre DAI/DS ?");
		});
	});
</script>
