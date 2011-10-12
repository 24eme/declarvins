<!-- #principal -->

<!-- #application_dr -->
<div class="btn" style="text-align: right;">
   <span>Vous êtes loggué en tant que <?php echo $interpro; ?></span>&nbsp; | &nbsp; <span><a class="modifier" href="<?php echo url_for('@validation_login') ?>">Déconnexion</a></span>
</div>
<script type="text/javascript">
    var interproLocked = new Array();
    <?php foreach($compte->getNbEtablissementByInterproId() as $id => $nb): ?>
    interproLocked.push("<?php echo $id ?>");
    <?php endforeach; ?>
</script>
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
    <ul class="chais">
        <?php foreach ($etablissements as $etablissement): ?>
        <li class="presentation"<?php if ($etablissement->statut == _Tiers::STATUT_ARCHIVER): ?> style="opacity:0.5;"<?php endif; ?>>
            <?php include_partial('etablissement/view', array('etablissement' => $etablissement, 'interpro' => $interpro)) ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p><i>Aucun établissement importé à ce jour</i></p>
    <?php endif; ?>
    <h2 class="titre_principal">Import</h2>
    <a href="<?php echo url_for('@validation_import') ?>">Lancer l'import</a>
    <?php include_component('validation', 'formUploadCsv'); ?>
     <h2 class="titre_principal">Liaison interpro</h2>
    <?php include_partial('compte/form_liaison_interpro', array('form' => $formLiaison)) ?>
    <h2 class="titre_principal">Validation</h2>
    <?php include_partial('validation/validation', array('valide_interpro' => $valide_interpro, 'compte_active' => $compte_active, 'interpro' => $interpro)) ?>

</div>
<!-- fin #exploitation_administratif -->

<!-- fin #application_dr -->

<!-- fin #principal -->