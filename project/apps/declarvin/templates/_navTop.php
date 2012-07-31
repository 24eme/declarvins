<?php
use_helper('Text');
?>
<nav id="barre_navigation">
    <ul id="nav_principale">
        <!--<li>
            <a href="#">Accueil</a>
        </li>-->
        <?php if($sf_user->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_DRM)): ?>
        <li<?php if ($active == 'drm'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('drm_mon_espace', $sf_user->getEtablissement()) ?>">DRM</a>
        </li>
        <?php endif; ?>

        <?php if($sf_user->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_VRAC)): ?>
        <li<?php if ($active == 'vrac'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('@vrac') ?>">Contrat interprofessionnel</a>
        </li>
        <?php endif; ?>
        
        <li>
            <a href="#">Profil</a>
        </li>
    </ul>

    <ul id="actions_etablissement">
        <?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?><li class="backend"><a href="<?php echo url_for('@admin'); ?>"><?php echo $sf_user->getInterpro()->nom ?></a></li><?php  endif; ?>
        <li class="etablissement_courant"><a href="" title="<?php echo $sf_user->getTiers()->getDenomination();?> (<?php echo $sf_user->getTiers()->getRaisonSociale(); ?> <?php echo $sf_user->getTiers()->getIdentifiant();?>)"><span><?php echo truncate_text($sf_user->getTiers()->getDenomination(),20); ?></span></a></li>
        <li class="quitter"><a href="<?php echo url_for('@ac_vin_logout'); ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
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
