<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">
    <h1>Déclaration Récapitulative Mensuelle</h1>
    <p id="date_drm">Bienvenue sur votre espace DRM. Que pensez-vous faire ?</p>
    <div id="creation_compte" style="width:70%; float: left;">

        <br /><br />
        <ul>
                <!--<li><a href="#">Accédez à mon historique de DRM &raquo;</a></li>-->
        <?php if ($sf_user->getDrm()->isNew()): ?>
                    <li><a href="<?php echo url_for('@drm_init') ?>">Commencer ma DRM &raquo;</a></li>
        <?php else: ?>
                    <li><a href="<?php echo url_for('@drm_init') ?>">Continuer ma DRM en cours &raquo;</a></li>
        <?php endif; ?>
                <li><a href="<?php echo url_for('@drm_historique') ?>">Votre historique &raquo;</a></li>
        </ul>

    </div>
    <?php include_partial('global/aides') ?>
    <div style="clear:both;">&nbsp;</div>
</section>
