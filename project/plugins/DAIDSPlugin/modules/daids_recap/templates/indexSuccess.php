<?php include_component('global', 'navTop', array('active' => 'daids')); ?>

<section id="contenu">

    <?php include_partial('daids/header', array('daids' => $daids)); ?>
    <?php include_component('daids', 'etapes', array('daids' => $daids, 
                                                   'etape' => 'recapitulatif', 
                                                   'certification' => $config_lieu->getCertification()->getKey(), 
                                                   'pourcentage' => '30')); ?>
    <?php include_partial('daids/controlMessage'); ?>
    <!-- #principal -->
    <section id="principal">
        <div id="application_dr">
            
            <?php include_component('daids_recap', 'onglets', array('config_lieu' => $config_lieu, 
                                                                  'daids_lieu' => $daids_lieu)) ?>
            <div id="contenu_onglet">
            
            	<a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a>
            
                <?php include_partial('shortcutKeys') ?>
                
                <?php /*include_component('drm_recap', 'list', array('drm_lieu' => $drm_lieu, 
                                                                   'config_lieu' => $config_lieu,
                                                                   'produits' => $produits,
                                                                   'form' => $form,
                												   'detail' => $detail)); */ ?>
                <div id="btn_suiv_prec">
                    <?php if ($previous): ?>
                        <a href="<?php echo url_for('drm_recap_lieu', $previous) ?>" class="btn_prec">
                            <span>Produit précédent</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($next): ?>
                        <a href="<?php echo url_for('drm_recap_lieu', $next) ?>" class="btn_suiv">
                            <span>Produit suivant</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div id="btn_etape_dr">
            	<?php if ($previous_certif): ?>
                <a href="<?php echo url_for('daids_recap', $previous_certif) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('daids_informations', $daids) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>

                <?php if ($next_certif): ?>
                <a href="<?php echo url_for('daids_recap', $next_certif) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
                <?php else: ?>
                <a href="#" class="btn_suiv"> <!-- NEXT ETAPE -->
                    <span>Suivant</span>
                </a>
            	<?php endif; ?>
            	
            </div>

            <div class="ligne_btn" style="margin-top: 30px;">
                <a href="<?php //echo url_for('drm_delete', $drm) ?>" class="annuler_saisie btn_remise"><span>annuler la saisie</span></a>
            </div>

        </div>
    </section>
</section>


