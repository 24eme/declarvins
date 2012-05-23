<script type="text/javascript">
    var familles = '<?php echo json_encode(sfConfig::get('app_etablissements_familles')) ?>';
</script>
<?php if ($sf_user->hasFlash('success')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('success'); ?></p>
<?php endif; ?>

<!-- #contenu -->
<section id="contenu">
    <h1><strong>Étape 2 :</strong> Informations relatives à vos établissements</h1>
    <?php $intro = (count($contrat->etablissements) > 1) ? "vos établissements" : "votre établissement" ; ?>
    <p class="intro">Veuillez remplir les informations concernant votre nouvel établissement  :</p>
    
    <div id="principal">
        <ul id="onglets_principal">
            <li class="actif"><strong>Etablissement</strong></li>
        </ul>
        <div id="contenu_onglet">
            <form id="creation_compte" action="<?php echo url_for('@contrat_etablissement_nouveau'); ?>" method="post">
            <?php include_partial('contrat/formEtablissement', array('form' => $form, 'new' => true, "recapitulatif" => false, 'annulation' => true)); ?>
            </form>
        </div>
    </div>
    
</section>
