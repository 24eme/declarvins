<script type="text/javascript">
    var familles = '<?php echo json_encode(sfConfig::get('app_etablissements_familles')) ?>';
    var sousFamilleSelected = '<?php echo $form['sous_famille']->getValue() ?>';
</script>
<?php if ($sf_user->hasFlash('success')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('success'); ?></p>
<?php endif; ?>

<section id="contenu">
    <?php
    foreach ($contrat->etablissements as $etablissement):
        if ($etablissement->getKey() == $form->getObject()->getKey()):
            ?>
            <form id="creation_compte" method="post" action="<?php echo ($recapitulatif) ? url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) : url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey())); ?>">
                <h1>Étape 2 : <strong>Veuillez saisir les informations pour l'établissement <?php echo $etablissement->getKey() + 1; ?></strong></h1>
				<p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
                <!--<h2>Veuillez saisir les informations pour l'établissement <?php echo $etablissement->getKey() + 1; ?></h2>--> 
                <?php include_partial('contrat/formEtablissement', array('form' => $form, 'new' => false, "recapitulatif" => ($recapitulatif == 1))); ?>
            </form>
            <?php
        endif;
    endforeach;
    ?>
</section>
