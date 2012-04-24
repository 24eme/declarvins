<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'validation', 'pourcentage' => '100')); ?>

    <!-- #principal -->
    <section id="principal">
        <form action="<?php echo url_for('drm_validation', $drm) ?>" method="post">
            <?php echo $form->renderGlobalErrors() ?>
            <?php echo $form->renderHiddenFields() ?>
            <div id="application_dr">
                
                <?php if ($drmValidation->hasErrors() || $drmValidation->hasWarnings()) { ?>
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
                    </div>
                </div>
                <?php } ?>
                
                <div id="contenu_onglet" class="tableau_ajouts_liquidations">
                    <?php
                    if ($drmValidation->hasEngagements()) {
                        include_partial('engagements', array('drmValidation' => $drmValidation, 'form' => $form));
                    }
                    ?>
                </div>
                <div id="contenu_onglet">
                    <?php include_partial('drm/recap', array('drm' => $drm)) ?>
                </div>
            </div>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_declaratif', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <button type="submit" class="btn_suiv"<?php if ($drmValidation->hasErrors()): ?> disabled="disabled"<?php endif; ?>><span>Valider</span></button>
            </div>
        </form>
    </section>
</section>
