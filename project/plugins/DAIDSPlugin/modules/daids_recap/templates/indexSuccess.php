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
                <a href="<?php echo url_for('daids_validation', $daids) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
            	<?php endif; ?>
            	
            </div>
            
            <?php include_component('daids_recap', 'onglets', array('config_lieu' => $config_lieu, 
                                                                  'daids_lieu' => $daids_lieu)) ?>
            <div id="contenu_onglet">
            
            	<a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a>
            
                <?php include_partial('daids_recap/shortcutKeys') ?>
                
                <?php include_component('daids_recap', 'list', array('daids_lieu' => $daids_lieu, 
                                                                   'config_lieu' => $config_lieu,
                                                                   'produits' => $produits,
                                                                   'form' => $form,
                												   'configurationDAIDS' => $configurationDAIDS,
                												   'detail' => $detail)); ?>
                <div id="btn_suiv_prec">
                    <?php if ($previous): ?>
                        <a href="<?php echo url_for('daids_recap_lieu', $previous) ?>" class="btn_prec">
                            <span>Produit précédent</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($next): ?>
                        <a href="<?php echo url_for('daids_recap_lieu', $next) ?>" class="btn_suiv">
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
                <a href="<?php echo url_for('daids_validation', $daids) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
            	<?php endif; ?>
            	
            </div>

            <div class="ligne_btn" style="margin-top: 30px;">
                <a href="<?php echo url_for('daids_delete_one', $daids) ?>" class="annuler_saisie btn_remise"><span>annuler la saisie</span></a>
            </div>

        </div>
    </section>
</section>


