<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'recapitulatif', 'certification' => $config_appellation->getCertification()->getKey(), 'pourcentage' => '30')); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <!-- #principal -->
    <section id="principal">
        <div id="application_dr">
            
            <?php include_component('drm_recap', 'onglets', array('config_appellation' => $config_appellation, 'drm_appellation' => $drm_appellation)) ?>
            <div id="contenu_onglet">
            
            	<a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a>
            
                <?php include_partial('shortcutKeys') ?>

                <?php include_component('drm_recap', 'list', array('drm_appellation' => $drm_appellation, 'config_appellation' => $config_appellation, 'form' => $form)); ?>
                
                <div id="btn_suiv_prec">
                    <?php if ($previous): ?>
                        <a href="<?php echo url_for('drm_recap_appellation', $previous) ?>" class="btn_prec">
                            <span>Produit précédent</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($next): ?>
                        <a href="<?php echo url_for('drm_recap_appellation', $next) ?>" class="btn_suiv">
                            <span>Produit suivant</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <form action="<?php echo url_for('drm_recap', $drm->declaration->certifications->getFirst()) ?>" method="post">
                    <button type="submit" class="btn_suiv"><span>Suivant</span></button>
                </form>
            </div>
        </div>
    </section>
</section>
<?php //$run->end(); ?>


