<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php include_partial('global/navTop', array('active' => 'drm', 'etablissement' => $etablissement)); ?>


<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
	<?php if (!$hide_rectificative && !$drm->getHistorique()->hasDRMInProcess() && $drm->isRectifiable()): ?>
    <form method="get" action="<?php echo url_for('drm_rectificative', $drm) ?>">
        <button class="btn_passer_etape rectificative" type="submit">Soumettre une DRM rectificative</button>
    </form>
	<?php endif; ?>
    <!-- #principal -->
    <section id="principal">

        <?php if ($drm_suivante && $drm_suivante->isRectificative() && !$drm_suivante->isValidee()): ?>
            <div class="vigilance_list">
                <ul>
                    <li><?php echo MessagesClient::getInstance()->getMessage('msg_version_suivante') ?></li>
                </ul>
            </div>
        <?php endif; ?>
        <div id="contenu_onglet">

            <?php if ($drm->declaration->hasMouvement() && !$drm->declaration->hasStockEpuise()): ?>
                <?php include_partial('drm/recap', array('drm' => $drm)) ?>
                <?php include_partial('drm/droits', array('drm' => $drm)) ?>
            <?php else: ?>
                <?php include_partial('drm/pasDeMouvement', array('drm' => $drm)) ?>
            <?php endif; ?>

            
            <a id="telecharger_pdf" href="<?php echo url_for('drm_pdf', $drm) ?>">Télécharger le PDF</a>
            
            <div id="btn_etape_dr">
                <?php if ($drm_suivante && $drm_suivante->hasVersion() && !$drm_suivante->isValidee()): ?>
                    <a href="<?php echo url_for('drm_init', array('sf_subject' => $drm_suivante, 'reinit_etape' => 1)) ?>" class="btn_suiv">
                        <span>Passer à la DRM suivante</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>" class="btn_suiv">
                        <span>Retour à mon espace</span>
                    </a>
                <?php endif; ?>
            </div>

        </div>    
    </section>
    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$drm->getHistorique()->hasDRMInProcess() && $drm->isModifiable()): ?>
    <form method="get" action="<?php echo url_for('drm_modificative', $drm) ?>">
        <button style="float:left;" class="btn_passer_etape modificative" type="submit">Corriger la DRM</button>
    </form>
    <?php endif; ?>
</section>
