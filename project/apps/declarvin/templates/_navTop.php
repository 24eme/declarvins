<?php
use_helper('Text');
?>
<nav id="barre_navigation">
    <ul id="nav_principale">
        <!--<li>
            <a href="#">Accueil</a>
        </li>-->
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))): ?>
        <li<?php if ($active == 'drm'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>">DRM</a>
        </li>
        <?php endif; ?>

        <?php if($etablissement->hasDroit(EtablissementDroit::DROIT_VRAC) || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
        <li<?php if ($active == 'vrac'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('vrac_etablissement', $etablissement) ?>">Contrat interprofessionnel</a>
        </li>
        <?php endif; ?>
        
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))): ?>
        <li<?php if ($active == 'daids'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('daids_mon_espace', $etablissement) ?>">DAI/DS</a>
        </li>
        <?php endif; ?>
        
        <li<?php if ($active == 'profil'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('profil', $etablissement) ?>">Profil</a>
        </li>
    </ul>

    <ul id="actions_etablissement">
        <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <li class="backend">
                <a href="<?php echo url_for('@admin'); ?>"><?php echo $sf_user->getCompte() ?></a>
            </li>
        <?php  endif; ?>
        
        <li class="etablissement_courant">
            <a href="<?php echo url_for('profil', array('identifiant' => $etablissement->getIdentifiant())); ?>" title="<?php echo $etablissement->getDenomination();?> (<?php echo $etablissement->getRaisonSociale(); ?> <?php echo $etablissement->getIdentifiant();?>)">
                <span><?php echo truncate_text($etablissement->getDenomination(),20); ?></span>
            </a>
        </li>
        <li class="quitter"><a href="<?php echo url_for('@tiers') ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
    </ul>
</nav>


<?php if ($etablissement->statut == Etablissement::STATUT_ARCHIVE): ?>
	<div id="etablissement_archive">
		/!\ Cet établissement est archivé /!\
	</div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice')){ ?>
    <div id="flash_message">
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice'); ?></div>
    </div>
<?php } ?>

<?php if ($sf_user->hasFlash('error')){ ?>
    <div id="flash_message">
        <div class="flash_error"><?php echo esc_entities($sf_user->getFlash('error')); ?></div>
    </div>
<?php } ?>
