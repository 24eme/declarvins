<?php use_helper('Float'); ?>
<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 
                                                   'etape' => 'recapitulatif', 
                                                   'certification' => $config_lieu->getCertification()->getKey(), 
                                                   'pourcentage' => $percent)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <!-- #principal -->
    <section id="principal">
        <div id="application_dr">
            
            <div id="btn_etape_dr">
                <?php if ($previous): ?>
                <a href="<?php echo url_for('drm_recap_lieu', $previous) ?>" class="btn_prec">
                	<span>Précédent</span>
                </a>
            	<?php elseif ($previous_certif): ?>
                <a href="<?php echo url_for('drm_recap', $previous_certif) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
				
				
                <?php if ($next): ?>
                <a href="<?php echo url_for('drm_recap_lieu', $next) ?>" class="btn_suiv">
                	<span>Suivant</span>
                </a>
                <?php elseif ($next_certif): ?>
                <a href="<?php echo url_for('drm_recap', $next_certif) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_vrac', $drm) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
            	<?php endif; ?>
            </div>

            <?php include_component('drm_recap', 'onglets', array('config_lieu' => $config_lieu,
                                                                  'drm_lieu' => $drm_lieu)) ?>
            <div id="contenu_onglet">

            	<a href="" data-popup="#raccourci_clavier" class="btn_popup" data-popup-config="configDefaut">Raccourcis clavier</a>

                <?php include_partial('shortcutKeys') ?>

                <?php foreach ($drm->getProduitsReserveInterpro($drm_lieu->getHash()) as $p): ?>
                    <table style="width:100%; border:1px solid red;margin: 5px 0;">
                        <tr>
                            <td <?php if ($p->hasCapaciteCommercialisation()): ?>rowspan="3"<?php endif; ?> style="vertical-align : middle;text-align:center;"><strong><?php echo $p->getLibelle(); ?></strong></td>
                            <td align="right" style="padding: 5px;">
                                <?php if (!$p->getReserveInterpro()): ?>
                                    Réserve libérée
                                <?php else: ?>
                                    Volume  mis en réserve : <strong><?php echoLongFloat($p->getReserveInterpro()); ?></strong>&nbsp;hl
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php if ($p->hasCapaciteCommercialisation()): ?>
                            <tr><td align="right" style="padding: 0 5px;">Capacité de commercialisation : <strong><?php echoLongFloat($p->getCapaciteCommercialisation()); ?></strong>&nbsp;hl</td></tr>
                            <tr><td align="right" style="padding: 5px;">Sorties de chai depuis le 01/12/24 : <strong><?php echoLongFloat($p->getSuiviSortiesChais()); ?></strong>&nbsp;hl</td></tr>
                        <?php endif; ?>
                    </table>
                <?php endforeach; ?>

                <?php include_component('drm_recap', 'list', array('drm_lieu' => $drm_lieu,
                                                                   'config_lieu' => $config_lieu,
                                                                   'produits' => $produits,
                                                                   'form' => $form,
                												   'detail' => $detail,
                												   'drm' => $drm)); ?>
            </div>
            
            <div id="btn_etape_dr">
                <?php if ($previous): ?>
                <a href="<?php echo url_for('drm_recap_lieu', $previous) ?>" class="btn_prec">
                	<span>Précédent</span>
                </a>
            	<?php elseif ($previous_certif): ?>
                <a href="<?php echo url_for('drm_recap', $previous_certif) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php endif; ?>
				
				
                <?php if ($next): ?>
                <a href="<?php echo url_for('drm_recap_lieu', $next) ?>" class="btn_suiv">
                	<span>Suivant</span>
                </a>
                <?php elseif ($next_certif): ?>
                <a href="<?php echo url_for('drm_recap', $next_certif) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
                <?php else: ?>
                <a href="<?php echo url_for('drm_vrac', $drm) ?>" class="btn_suiv">
                    <span>Suivant</span>
                </a>
            	<?php endif; ?>
            	
            </div>
			
			<?php if($drm->isRectificative() && $drm->exist('ciel') && $drm->ciel->transfere): ?>
			<?php else: ?>
            <div class="ligne_btn">
                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
            </div>
			<?php endif; ?>
        </div>
    </section>
</section>


