<?php if(!($form->getEtablissement() && $form->getEtablissement()->famille == EtablissementFamilles::FAMILLE_COURTIER)): ?>
<div class="contenu_onglet" data-cible="vrac_mandataire">
    <?php echo $form['mandataire_exist']->renderError() ?>
    <?php echo $form['mandataire_exist']->renderLabel() ?>
    <?php echo $form['mandataire_exist']->render() ?>
</div>
<?php endif; ?>

<div id="mandataire" class="vrac_mandataire">
    <h1>Courtier</h1>
    <h2>Sélectionner un courtier :</h2>

    <div class="soussigne_form_choice">
        <div class="section_label_strong" id="listener_mandataire_choice">
            <?php echo $form['mandataire_identifiant']->renderError() ?>
            <label for="">Nom :</label>
            <?php echo $form['mandataire_identifiant']->render() ?>
        </div>
        <div  class="bloc_form" id="etablissement_mandataire"> 
            <?php include_partial('form_mandataire', array('form' => $form['mandataire'])); ?>
        </div>
    </div>
    
    <?php if ($form->getEtablissement()): ?>
    <div class="soussigne_info">
        <?php echo $form->getEtablissement()->getNom() ?>
    </div>
    <?php endif; ?>
</div>