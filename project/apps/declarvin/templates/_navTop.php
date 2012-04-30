<nav id="barre_navigation">
    <ul id="nav_principale">
        <li>
            <a href="#">Accueil</a>
        </li>
        <li<?php if ($active == 'drm'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('@drm_mon_espace') ?>">DRM</a>
        </li>
        <li<?php if ($active == 'vrac'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('@vrac') ?>">Vrac</a>
        </li>
        <li>
            <a href="#">DAI/DS</a>
        </li>
        <li>
            <a href="#">DR</a>
        </li>
        <li>
            <a href="#">Profil</a>
        </li>
        <li>
            <a href="#">Divers</a>
        </li>
    </ul>

    <ul id="actions_etablissement">
        <li class="etablissement_courant"><a href=""><span><?php echo $sf_user->getTiers()->nom ?></span></a></li>
        <li class="quitter"><a href="<?php echo url_for('@tiers') ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
    </ul>
</nav>


<?php if ($sf_user->hasFlash('notice')){ ?>
    <div id="flash_message">
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice'); ?></div>
    </div>
<?php } ?>

<?php if ($sf_user->hasFlash('error')){ ?>
    <div id="flash_message">
        <div class="flash_error"><?php echo $sf_user->getFlash('error'); ?></div>
    </div>
<?php } ?>
