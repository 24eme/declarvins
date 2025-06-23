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
            <a href="<?php echo url_for('vrac_etablissement', ['identifiant' => $etablissement->identifiant]) ?>">Contrat</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'sv12') && ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)||(isset($isAdmin)&&$isAdmin))): ?>
        <?php if($etablissement->hasDroit(EtablissementDroit::DROIT_SV12)): ?>
        <li<?php if ($active == 'sv12'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('sv12_etablissement', $etablissement) ?>">SV12</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'dsnegoce')): ?>
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DSNEGOCEUPLOAD))): ?>
        <li<?php if ($active == 'dsnegoce'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('dsnegoceupload_mon_espace', $etablissement) ?>">DS Négoce</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($configuration->isApplicationOuverte('INTERPRO-CIVP', 'ds') && $etablissement->hasZone(ConfigurationZoneClient::ZONE_PROVENCE) && $etablissement->hasDroit(EtablissementDroit::DROIT_DS)): ?>
        <li<?php if ($active == 'ds'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('ds_etablissement', $etablissement) ?>"><?php echo DSConfiguration::getInstance()->getName() ?></a>
        </li>
        <?php endif; ?>
        <?php if ($etablissement->interpro == 'INTERPRO-IR'): ?>
        <style><!--
            #barre_navigation #nav_principale li.vrr a:hover {
                background: url('<?php echo image_path('/images/fonds/bg_nav_principale_vvr.png', true) ?>') left 0 no-repeat;
            }
        --></style>
        <li class="vrr">
            <a href="<?php echo url_for('redirect2stat', $etablissement); ?>">Statistiques</a>
        </li>
        <?php endif; ?>
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'dae')): ?>
        <li<?php if ($active == 'dae'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('dae_etablissement', $etablissement) ?>">Commercialisation</a>
        </li>
        <?php endif; ?>
        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'factures')||$etablissement->hasFacture()): ?>
        <li<?php if ($active == 'factures'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('facture_societe', $etablissement) ?>">
            Factures
            <?php
                $f = $etablissement->getLastFacture();
                if($f && !$f->isTelechargee()):
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16" style="position: relative; top: 3px;">
              <title>Votre dernière facture n'a pas été consultée</title>
              <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
            </svg>
            <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if ($configuration->isApplicationOuverte($etablissement->interpro, 'cravate', $etablissement)): ?>
        <?php if(($etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI))): ?>
        <li<?php if ($active == 'cravate'): ?> class="actif"<?php endif; ?>>
            <a href="/cravate-pdf/procedures/<?php echo $etablissement->identifiant ?>">Dossiers</a>
        </li>
        <?php endif; ?>
        <?php endif; ?>

        <?php if(($etablissement->hasDocuments()) || ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)||(isset($isAdmin)&&$isAdmin))): ?>
        <li<?php if ($active == 'documents'): ?> class="actif"<?php endif; ?>>
            <a href="<?php echo url_for('fichiers_etablissement', $etablissement) ?>">Documents</a>
        </li>
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
    	<li style="margin:0;"><span style="cursor: auto;" class="<?php if($etablissement->isTransmissionCiel()): ?>ciel_connect<?php else: ?>ciel_disconnect<?php endif; ?>" title="<?php if($etablissement->isTransmissionCiel()): ?>Transmission CIEL activée<?php else: ?>Aucune transmission CIEL<?php endif; ?>">&nbsp;</span></li>
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
        <li class="quitter"><a href="<?php echo url_for('@tiers') ?>"><img src="<?php echo image_path('/images/boutons/btn_quitter_etablissement.png', true) ?>" alt="Quitter cet établissement"></a></li>
    </ul>
</nav>
<?php $compte = $etablissement->getCompteObject(); if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement && $compte): ?>
<div style="text-align: right; background: #fff; height: 16px; padding-top: 5px;">
<a href="<?php echo url_for('tiers_connexion_email', array('login' => $compte->login)); ?>" style="background: url('<?php echo image_path('/images/pictos/pi_usurpation_in.png', true) ?>') left 0 no-repeat; padding: 0px 5px 0 20px;">Accéder au compte</a>
</div>
<?php  endif; ?>
<?php if ($etablissement && $sf_user->isUsurpationMode()): ?>
<div style="text-align: right; background: #fff; height: 16px; padding-top: 5px;">
<a href="<?php echo url_for('tiers_connexion_initial', $etablissement); ?>" style="background: url('<?php echo image_path('/images/pictos/pi_usurpation_out.png', true) ?>') left 0 no-repeat; padding: 0px 5px 0 20px;">Retour compte admin</a>
</div>
<?php  endif; ?>

<?php if ($etablissement->statut == Etablissement::STATUT_ARCHIVE): ?>
	<div id="etablissement_archive">
		/!\ Cet établissement est archivé /!\
	</div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice')){ ?>
    <div id="flash_message" style="padding-top: 0px">
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice'); ?></div>
    </div>
<?php } ?>

<?php if ($sf_user->hasFlash('error')){ ?>
    <div id="flash_message" style="padding-top: 0px">
        <div class="flash_error"><?php echo esc_entities($sf_user->getFlash('error')); ?></div>
    </div>
<?php } ?>


<?php
    $info = null;
    if ($active == 'drm') {
       $info = MessagesClient::getInstance()->getDRMInfos($etablissement->interpro);
    }
    if ($active == 'vrac') {
       $info = MessagesClient::getInstance()->getVracInfos($etablissement->interpro);
    }
?>
<?php if ($info): ?>
<div id="flash_message" style="padding-top: 0px">
    <div class="flash_error" style="color: #fff; background-color: #8faf6e; border: 1px solid #4D6D2C;">
    	<h2 style="font-size: 14px; height: 32px; line-height: 28px; padding: 0 0 5px 0; margin: 0; font-weight: bold; " ><img src="<?php echo image_path('/images/pictos/info2.png', true) ?>" style="float: left; height: 32px;" />&nbsp;Alertes / Infos</h2>
    	<?php echo $info ?>
	</div>
</div>
<?php endif; ?>
