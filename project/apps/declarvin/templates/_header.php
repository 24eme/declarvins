<!-- #header -->
<header id="header">
    <nav>
        <ul>
            <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            <li class="backend"><a href="<?php echo url_for('@admin'); ?>">Interface de Gestion</a></li>
            <?php  endif; ?>
            <li><a href="<?php echo url_for('@contact') ?>" target="_blank">Contact</a></li>
        </ul>
    </nav>

    <div id="logo">
        <h1><a href="<?php echo url_for('ac_vin_login') ?>" title="Declarvin - Retour à l'accueil"><img src="/images/visuels/logo_declarvins.png" alt="Declarvin" /></a></h1>
        <p style="font-weight: bold; text-align:center;">/!\ PREPROD /!\</p>
    </div>
    <?php if ($sf_user->isAuthenticated()): ?>
    <p class="deconnexion"><a href="<?php echo url_for('@ac_vin_logout'); ?>">Se deconnecter</a></p>
    <?php endif; ?>
</header>
<!-- fin #header -->