<!-- #header -->
<header id="header">
    <nav>
        <ul>
            <?php if($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)||$isAdmin): ?>
            <li class="backend"><a href="<?php echo url_for('@admin'); ?>">Interface de Gestion</a></li>
        <?php elseif ($sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute): ?>
            <li class="backend"><a href="<?php echo url_for('ciel_help', $sf_request->getAttribute('sf_route')->getEtablissement()); ?>">Assistance DeclarVins</a></li>
            <?php  endif; ?>
            <li><a href="<?php echo url_for('@contact') ?>">Contact</a></li>
        </ul>
    </nav>

    <div id="logo">
        <h1><a href="<?php echo url_for('ac_vin_login') ?>" title="Declarvin - Retour à l'accueil"><img src="<?php echo image_path("/images/visuels/logo_declarvins.png", true) ?>" alt="Declarvin" /></a></h1>
        <?php if(sfConfig::get('app_instance') == 'preprod'||strpos($_SERVER['HTTP_HOST'], 'preprod') !== false): ?>
        <p style="font-weight: bold; text-align:center;">/!\ PREPROD V2 /!\</p>
        <?php else: ?>
        <p>La plateforme déclarative des vins du Rhône, de Provence et du Sud Est</p>
        <?php endif; ?>
        <!-- <p style="font-weight: bold; text-align:center; background-color:#f0ad4e; margin:10px 0; padding: 10px 0;">/!\<br>Madame, Monsieur,<br> La plateforme DeclarVins sera en maintenance et donc indisponible du jeudi 13 Juin 12h00 au vendredi 14 Juin 2019 vers 10h00.<br>
						Veuillez nous excuser pour la gêne occasionnée.<br>/!\</p>  -->
    </div>
    <?php if ($sf_user->isAuthenticated()||$isAdmin): ?>
    <p class="deconnexion"><a href="<?php echo url_for('@ac_vin_logout'); ?>">Se deconnecter</a></p>
    <?php endif; ?>
</header>
<!-- fin #header -->
