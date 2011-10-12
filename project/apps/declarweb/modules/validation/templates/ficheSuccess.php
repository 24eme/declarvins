<!-- #principal -->

<!-- #application_dr -->
<div class="btn" style="text-align: right;">
   <span>Vous êtes loggué en tant que <?php echo $interpro; ?></span>&nbsp; | &nbsp; <span><a class="modifier" href="<?php echo url_for('@validation_login') ?>">Déconnexion</a></span>
</div>
<?php if($sf_user->hasFlash('general')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('general'); ?></p>
<?php endif; ?>
<div class="clearfix" id="application_dr">
    <h2 class="titre_principal">Compte</h2>
    <!-- #exploitation_administratif -->
    <div id="mon_compte">
        <?php include_partial('compte/view_form', array('form' => $form, 'compte' => $compte))?>
    </div>
    <h2 class="titre_principal">Etablissements associés</h2>
    <?php if (count($etablissements) > 0): ?>
    <ul class="etablissements">
        <?php foreach ($etablissements as $etablissement): ?>
        <li class="presentation">
            <?php include_partial('etablissement/view', array('etablissement' => $etablissement, 'interpro' => $interpro)) ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p><i>Aucun établissement importé à ce jour</i></p>
    <?php endif; ?>
    <h2 class="titre_principal">Import</h2>
    <a href="<?php echo url_for('@validation_import') ?>">Lancer l'import</a>
</div>
<!-- fin #exploitation_administratif -->

<!-- fin #application_dr -->

<!-- fin #principal -->