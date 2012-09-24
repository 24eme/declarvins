<div id="bloc_<?php echo $famille ?>" class="vrac_vendeur_acheteur">
    <h1><?php echo $titre ?></h1>

    <div id="bloc_<?php echo $famille ?>_choice" class="soussigne_form_choice <?php if (isset($form['vous_etes'])): ?>bloc_conditionner<?php endif; ?>" data-condition-value="<?php echo $famille_autre ?>">
        <h2><?php echo $sous_titre ?></h2>
        <div class="section_label_strong etablissement_famille_choice">
            <?php echo $form[$famille.'_type']->renderLabel() ?>
            <?php echo $form[$famille.'_type']->renderError() ?>
            <?php echo $form[$famille.'_type']->render(array('class' => 'famille', 'data-template' => '#template_url_etablissement', 'data-container' => '#'.$form[$famille.'_identifiant']->renderId())) ?>
        </div>
        <div class="section_label_strong etablissement_choice" id="listener_<?php echo $famille ?>_choice">
            <?php echo $form[$famille.'_identifiant']->renderError() ?>
            <label for="">Nom :</label>
            <?php echo $form[$famille.'_identifiant']->render(array('data-infos-container' => '#etablissement_informations_'.$famille)) ?>
        </div>

        <div class="bloc_form etablissement_informations" id="etablissement_informations_<?php echo $famille ?>"> 
            <?php include_partial('form_etablissement', array('etablissement' => $form->getEtablissement(), 'form' => $form[$famille])); ?>
        </div>
    </div>

    <?php if ($form->getEtablissement()): ?>
    <div id="bloc_<?php echo $famille ?>_vous" class="soussigne_vous bloc_conditionner" data-condition-value="<?php echo $famille ?>">
        <h2><?php echo $sous_titre_vous ?></h2>
        <div class="section_label_strong etablissement_famille_choice">
            <label for="">Type :</label>
            <?php echo $form->getEtablissement()->getFamille() ?>
        </div>
        <div class="section_label_strong etablissement_choice">
            <label for="">Nom :</label>
            <?php echo $form->getEtablissement()->getNom() ?>
        </div>

        <div class="bloc_form etablissement_informations"> 
            <?php include_partial('form_etablissement', array('etablissement' => $form->getEtablissement(), 'form' => $form[$famille])); ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="section_label_strong">
        <?php echo $form[$famille.'_tva']->renderError() ?> 
        <?php echo $form[$famille.'_tva']->renderLabel() ?>
        <?php echo $form[$famille.'_tva']->render() ?>
    </div>
    
    <?php include_partial('vrac/form_soussigne_adresse', array('form' => $form, 
                                                              'field' => $field_adresse,
                                                              'label_adresse' => $label_adresse)); ?>
</div>