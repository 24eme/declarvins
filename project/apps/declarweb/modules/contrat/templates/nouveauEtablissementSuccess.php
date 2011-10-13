<script type="text/javascript">
    var familles = '<?php echo json_encode(sfConfig::get('app_etablissements_familles')) ?>';
</script>
<?php if ($sf_user->hasFlash('success')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('success'); ?></p>
<?php endif; ?>

<!-- #contenu -->
<section id="contenu">
    <form id="creation_compte" action="<?php echo url_for('@contrat_etablissement_nouveau'); ?>" method="post">
        <h1>Étape 2 : <strong>Création de compte</strong></h1>

        <h2>Veuillez saisir les informations pour le nouvel établissement</h2>
        <?php include_partial('contrat/formEtablissement', array('form' => $form, 'new' => true, 'annulation' => true)); ?>
    </form>
</section>