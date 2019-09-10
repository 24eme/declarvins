<?php
use_helper('Text');
?>
<nav id="barre_navigation">
    <ul id="nav_principale">
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'drm', $etablissement)): ?>
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))): ?>
        <li<?php if ($active == 'drm'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('drm_mon_espace', $etablissement) ?>">DRM</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'vrac')): ?>
        <?php if($etablissement->hasDroit(EtablissementDroit::DROIT_VRAC)): ?>
        <li<?php if ($active == 'vrac'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('vrac_etablissement', $etablissement) ?>">Contrat interprofessionnel</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>        
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'daids')): ?>
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))): ?>
        <li<?php if ($active == 'daids'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('daids_mon_espace', $etablissement) ?>">DAI/DS</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>        
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'dsnegoce')): ?>
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DSNEGOCE))): ?>
        <li<?php if ($active == 'dsnegoce'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('dsnegoce_mon_espace', $etablissement) ?>">DS Négoce</a>
        </li>
        <?php endif; ?>
        <?php endif; ?> 
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'dae')): ?>
        <?php if($etablissement->hasDroit(EtablissementDroit::DROIT_DAE)): ?>
        <li<?php if ($active == 'dae'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('dae_etablissement', $etablissement) ?>">Commercialisation</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>  
        
        <li<?php if ($active == 'profil'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('profil', $etablissement) ?>">Profil</a>
        </li>
        
        <?php if (!$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $configuration->isApplicationOuverte($etablissement->interpro, 'drm', $etablissement)): ?>
        <?php if($etablissement->canAdhesionCiel() && !$etablissement->isTransmissionCiel()): ?>
        <?php 
            $convention = $sf_user->getCompte()->getConventionCiel();
            if (!$convention || !$convention->valide): 
        ?>
        <li<?php if ($active == 'ciel'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('convention_ciel', $etablissement) ?>"><strong>Adhésion CIEL</strong></a>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
    </ul>
    <?php if ($etablissement->canAdhesionCiel()): ?>
    <ul id="nav_infociel">
    	<li><span style="cursor: auto;" class="<?php if($etablissement->isTransmissionCiel()): ?>ciel_connect<?php else: ?>ciel_disconnect<?php endif; ?>" title="<?php if($etablissement->isTransmissionCiel()): ?>Transmission CIEL activée<?php else: ?>Aucune transmission CIEL<?php endif; ?>">CIEL</span></li>
    	<?php if($etablissement->isTransmissionCiel()): ?>
    	<li><a href="<?php echo url_for('ciel_help', $etablissement) ?>"><span class="ciel_help" title="Assistance CIEL">&nbsp;</span></a></li>
    	<?php endif; ?>
    </ul>
    <?php endif; ?>
</nav>
<nav id="sous_barre_navigation">
	<ul id="actions_etablissement">
        <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <li class="backend">
                <a href="<?php echo url_for('@admin'); ?>"><?php echo $sf_user->getCompte() ?></a>
            </li>
        <?php  endif; ?>
        
        <li class="etablissement_courant">
            <a href="<?php echo url_for('profil', $etablissement) ?>" title="<?php echo $etablissement->getDenomination();?> (<?php echo $etablissement->getRaisonSociale(); ?> <?php echo $etablissement->getIdentifiant();?>)">
                <span><?php echo $etablissement->getDenomination(); ?></span>
            </a>
        </li>
        <li class="quitter"><a href="<?php echo url_for('@tiers') ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
    </ul>
</nav>
<?php $compte = $etablissement->getCompteObject(); if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement && $compte): ?>
<div style="text-align: right; background: #fff; height: 16px; padding-top: 5px;">
<a href="<?php echo url_for('tiers_connexion_email', array('login' => $compte->login)); ?>" style="background: url('/images/pictos/pi_anonyme.png') left 0 no-repeat; padding: 2px 5px 0 20px;">Accéder au compte</a>
</div>
<?php  endif; ?>
<?php if ($etablissement && $sf_user->getAttribute('initial_user')): ?>
<div style="text-align: right; background: #fff; height: 16px; padding-top: 5px;">
<a href="<?php echo url_for('tiers_connexion_initial', $etablissement); ?>" style="background: url('/images/pictos/pi_anonyme.png') left 0 no-repeat; padding: 2px 5px 0 20px;">Retour compte admin</a>
</div>
<?php  endif; ?>

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
