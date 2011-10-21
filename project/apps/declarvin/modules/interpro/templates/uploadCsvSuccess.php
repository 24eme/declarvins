<div class="btn" style="text-align: right;">
    <span>Vous êtes loggué en tant que <?php echo $interpro; ?></span>&nbsp; | &nbsp; <span><a class="modifier" href="<?php echo url_for('@validation_fiche') ?>">Retour à la fiche</a></span>
</div>
<?php if ($sf_user->hasFlash('notification_general')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('notification_general'); ?></p>
<?php endif; ?>
<div class="clearfix" id="application_dr">
    <h2 class="titre_principal">Import des établissements</h2>
    <?php if (@file_get_contents($interpro->getAttachmentUri('etablissements.csv'))): ?>
        <p>
            Fichier prêt pour l'import (<a href="<?php echo $interpro->getAttachmentUri('etablissements.csv'); ?>">télécharger le fichier</a>) => <a href="<?php echo url_for("interpro_import", array('id' => $interpro->get('_id'))) ?>">Lancer l'update</a>
        </p> 
        <br />
    <?php else: ?>
        <p>
        <i>Vous n'avez pas encore chargé de fichier d'import.</i>
        </p>
        <br />
    <?php endif; ?>
    <?php include_partial('interpro/formUploadCsv', array('form' => $formUploadCsv, 'interpro' => $interpro)) ?>
</div>