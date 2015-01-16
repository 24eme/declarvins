<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php include_component('global', 'navTop', array('active' => 'drm')); ?>


<section id="contenu">
    <?php if ($sf_user->hasFlash('erreur_drm')): ?>
        <div id="flash_message">
            <div class="flash_error"><?php echo $sf_user->getFlash('erreur_drm') ?></div>
        </div>
    <?php endif; ?>
    <?php if($masterVersion->_id != $drm->_id): ?>
    <div id="flash_message">
        <div class="flash_warning">La version de DRM que vous visualisez n'est pas la version la plus récente. <a href="<?php echo url_for('drm_visualisation', array('sf_subject' => $masterVersion)) ?>">Cliquez ici</a> pour visualiser la dernière version.</div>
        </div>
<?php endif; ?>
    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php if ($etablissement->statut != Etablissement::STATUT_ARCHIVE || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
        <?php if (!$hide_rectificative && !$drm->getHistorique()->hasDRMInProcess() && $drm->isRectifiable()): ?>
            <form method="get" action="<?php echo url_for('drm_rectificative', $drm) ?>">
                <button class="btn_passer_etape rectificative" type="submit">Soumettre une DRM rectificative</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
    <!-- #principal -->
    <section id="principal">

        <?php if ($drm->isValidee()): ?>
            <div style="background: none repeat scroll 0 0 #ECFEEA;border: 1px solid #359B02;color: #1E5204;font-weight: bold;margin: 0 0 10px 0;padding: 5px 10px;">
                <ul>
                    <li>Votre DRM a bien été saisie et validée</li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($drm_generate_version || $drm_next_version): ?>
            <div class="vigilance_list">
                <ul>
                	<?php if ($drm_generate_version > 0): ?>
                	<li><?php echo $drm_generate_version ?> DRM<?php if($drm_generate_version > 1): ?>s<?php endif; ?> modificative<?php if($drm_generate_version > 1): ?>s<?php endif; ?> générée<?php if($drm_generate_version > 1): ?>s<?php endif; ?> automatiquement</li>
                	<?php endif; ?>
                	<?php if ($drm_next_version && $drm_next_version->isModificative() && !$drm_next_version->isValidee()): ?>
                    <li>Vous devez modifier la DRM <?php echo $drm_next_version->periode ?></li>
                    <?php endif; ?>
                    <?php if ($drm_next_version && $drm_next_version->isRectificative() && !$drm_next_version->isValidee()): ?>
                    <li>Vous devez rectifier la DRM <?php echo $drm_next_version->periode ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div id="contenu_onglet">
            <?php if ($drm_precedente_version->_id != $drm->_id): ?>
<!--                <div class="tableau_ajouts_liquidations">
                    <a href="<?php echo url_for('drm_visualisation', array('sf_subject' => $drm_precedente_version)) ?>" class="btn_etape_prec" ><span>Accéder à la version précédente</span></a>
                </div>-->
            <?php endif; ?>
            <?php if ($drm->declaration->hasMouvement() && !$drm->declaration->hasStockEpuise()): ?>
                <?php include_partial('drm/recap', array('drm' => $drm)) ?>
                <?php include_partial('drm/droits', array('drm' => $drm, 'droits_circulation' => $droits_circulation)) ?>
            <?php else: ?>
                <?php include_partial('drm/pasDeMouvement', array('drm' => $drm)) ?>
            <?php endif; ?>


            <?php if ($drm->isIncomplete()): ?>
                <div class="vigilance_list">
                    <ul>
                        <li style="padding-bottom: 5px;">DRM incomplète :</li>
                        <?php if ($drm->manquants->igp): ?>
                            <li style="font-weight: normal;">Produit(s) IGP manquant(s)</li>
                        <?php endif; ?>
                        <?php if ($drm->manquants->contrats): ?>
                            <li style="font-weight: normal;">Contrat(s) manquant(s)</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
			<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <?php include_partial('drm/mouvements', array('mouvements' => $mouvements, 'etablissement' => $etablissement, 'hamza_style' => false, 'no_link' => null)) ?>
            <?php endif; ?>
            <br/>
        <?php if ($drm->commentaires): ?>
            <div style="padding: 0 0 30px 0">
                <strong>Commentaires</strong>
                <pre style="background: #fff; border: 1px #E9E9E9; padding: 8px; margin-top: 8px;"><?php echo $drm->commentaires ?></pre>
            </div>
        <?php endif; ?>   
            
            <a id="telecharger_pdf" href="<?php echo url_for('drm_pdf', $drm) ?>">Télécharger le PDF</a>

            <div id="btn_etape_dr">
                <?php if ($drm_next_version && $drm_next_version->hasVersion() && !$drm_next_version->isValidee()): ?>
                    <a href="<?php echo url_for('drm_init', array('sf_subject' => $drm_next_version, 'reinit_etape' => 1)) ?>" class="btn_suiv">
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
            <button style="float:left;" class="btn_passer_etape modificative" type="submit">Faire une DRM Modificative</button>
        </form>
    <?php endif; ?>
</section>
