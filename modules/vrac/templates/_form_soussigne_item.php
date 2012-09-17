<div id="bloc_<?php echo $famille ?>" class="vrac_vendeur_acheteur">
    <h1><?php echo $titre ?></h1>

    <div id="bloc_<?php echo $famille ?>_choice" class="soussigne_form_choice <?php if ($form->getEtablissement()): ?>bloc_conditionner<?php endif; ?>" data-condition-value="<?php echo $famille_autre ?>">
        <h2><?php echo $sous_titre ?></h2>
        <div class="section_label_strong etablissement_famille_choice">
            <?php echo $form[$famille.'_type']->renderLabel() ?>
            <?php echo $form[$famille.'_type']->renderError() ?>
            <?php echo $form[$famille.'_type']->render(array('class' => 'famille', 'data-template' => '#template_url_etablissement', 'data-container' => '#'.$form[$famille.'_identifiant']->renderId())) ?>
        </div>
        <div class="section_label_strong etablissement_choice" id="listener_acheteur_choice">
            <?php echo $form[$famille.'_identifiant']->renderError() ?>
            <label for="">Nom :</label>
            <?php echo $form[$famille.'_identifiant']->render() ?>
        </div>

        <div class="bloc_form etablissement_informations" id="etablissement_<?php echo $famille ?>"> 
            <?php include_partial('form_etablissement', array('form' => $form[$famille.''])); ?>
        </div>
    </div>

    <?php if ($form->getEtablissement()): ?>
    <div id="bloc_<?php echo $famille ?>_vous" class="soussigne_vous bloc_conditionner" data-condition-value="<?php echo $famille ?>">
        <h2><?php echo $form->getEtablissement()->getNom() ?></h2>
    </div>
    <?php endif; ?>

    <div class="section_label_strong">
        <?php echo $form[$famille.'_tva']->renderError() ?> 
        <?php echo $form[$famille.'_tva']->renderLabel() ?>
        <?php echo $form[$famille.'_tva']->render() ?>
    </div>
    
    <div class="adresse_livraison">
        <div class="section_label_strong">
            <label for="dif_adr_livr"><input type="checkbox" name="dif_adr_livr" id="dif_adr_livr" <?php if($form->getObject()->adresse_livraison->adresse): echo 'checked="checked"'; endif; ?>> Adresse de livraison différente</label>
        </div>
        <div class="bloc_form"> 
            <div class="vracs_ligne_form vracs_ligne_form_alt">
                <span>
                    <?php echo $form['adresse_livraison']['libelle']->renderError() ?>
                    <?php echo $form['adresse_livraison']['libelle']->renderLabel() ?>
                    <?php echo $form['adresse_livraison']['libelle']->render() ?>
                </span>
            </div>
            <div class="vracs_ligne_form ">
                <span>
                    <?php echo $form['adresse_livraison']['adresse']->renderError() ?>
                    <?php echo $form['adresse_livraison']['adresse']->renderLabel() ?>
                    <?php echo $form['adresse_livraison']['adresse']->render() ?>
                </span>
            </div>
            <div class="vracs_ligne_form vracs_ligne_form_alt">
                <span>
                    <?php echo $form['adresse_livraison']['code_postal']->renderError() ?>
                    <?php echo $form['adresse_livraison']['code_postal']->renderLabel() ?>
                    <?php echo $form['adresse_livraison']['code_postal']->render() ?>
                </span>
            </div>
            <div class="vracs_ligne_form ">
                <span>
                    <?php echo $form['adresse_livraison']['commune']->renderError() ?>
                    <?php echo $form['adresse_livraison']['commune']->renderLabel() ?>
                    <?php echo $form['adresse_livraison']['commune']->render() ?>
                </span>
            </div>
        </div>
    </div>
</div>