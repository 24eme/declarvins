<!-- #header -->
<header id="header">
    <nav>
        <ul>
            <?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
            <li class="backend"><a href="<?php echo url_for('@admin'); ?>">Backend</a></li>
            <?php  endif; ?>
            <li><a href="#">Contact</a></li>
            <li><a href="#">FAQ</a></li>
            <li class="profil"><a href="#">Mon profil</a></li>
        </ul>
    </nav>

    <div id="logo">
        <h1><a href="#" title="Declarvin - Retour à l'accueil"><img src="/images/visuels/logo_declarvins.png" alt="Declarvin" /></a></h1>
        <p>La plateforme déclarative des vins du Rhône, de Provence et du Sud Est</p>
    </div>
    <p class="deconnexion"><?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?><a href="<?php echo url_for('@admin'); ?>" class="backend_link">Accès au Backend</a> - <?php  endif; ?><a href="<?php echo url_for('@ac_vin_logout'); ?>">Se deconnecter</a></p>
</header>
<!-- fin #header -->