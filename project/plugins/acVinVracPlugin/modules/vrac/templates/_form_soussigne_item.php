<div id="bloc_<?php echo $famille ?>" class="vrac_vendeur_acheteur">
    <h1><?php echo $titre ?> <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_informations" title="Message aide"></a></h1>
    <?php 
    	if ($form->getObject()->vous_etes == $famille): 
    		$etablissement = ($form->getEtablissement())? $form->getEtablissement() : $form->getObject()->getCreateur(true);
    ?>
    <div id="bloc_<?php echo $famille ?>_vous" class="soussigne_vous <?php if($etablissement && $etablissement->famille == 'negociant' && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>bloc_conditionner<?php endif; ?>" data-condition-value="<?php echo $famille ?>">
        <h2><?php echo $sous_titre_vous ?></h2>
        <div class="section_label_strong etablissement_famille_choice">
            <label for="">Type :</label>
            <?php echo $etablissement->getFamille() ?>
        </div>
        <div class="section_label_strong etablissement_choice">
            <label for="">Nom :</label>
            <?php echo $etablissement->raison_sociale ?><?php if ($etablissement->raison_sociale && $etablissement->nom): ?> / <?php endif; ?><?php echo $etablissement->nom ?>
        </div>

        <div class="bloc_form etablissement_informations"> 
            <?php include_partial('form_etablissement_defaut', array('etablissement' => $etablissement, 'form' => $form[$famille])); ?>
        </div>
    </div>
    <?php if($etablissement && $etablissement->famille == 'negociant' && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    <div id="bloc_<?php echo $famille ?>_choice" class="soussigne_form_choice " data-condition-value="<?php echo $famille_autre ?>">
        <h2><?php echo $sous_titre ?></h2>
        <div class="section_label_strong etablissement_choice" id="listener_<?php echo $famille ?>_choice">
            <?php echo $form[$famille.'_identifiant']->renderError() ?>
            <label for="">Nom : <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_manquant" title="Message aide"></a></label>
            <?php echo $form[$famille.'_identifiant']->render(array('data-remove-inputs' => '#etablissement_informations_'.$famille, 'data-infos-container' => '#etablissement_informations_'.$famille)) ?>
        </div>

        <div class="bloc_form etablissement_informations" id="etablissement_informations_<?php echo $famille ?>"> 
            <?php include_partial('form_etablissement', array('form' => $form[$famille])); ?>
        </div>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <?php if ($form->getEtablissement()): ?>
    <div id="bloc_<?php echo $famille ?>_vous" class="soussigne_vous bloc_conditionner" data-condition-value="<?php echo $famille ?>">
        <h2><?php echo $sous_titre_vous ?></h2>
        <div class="section_label_strong etablissement_famille_choice">
            <label for="">Type :</label>
            <?php echo $form->getEtablissement()->getFamille() ?>
        </div>
        <div class="section_label_strong etablissement_choice">
            <label for="">Nom :</label>
            <?php if ($form->getEtablissement()->raison_sociale): ?> / <?php endif; ?><?php echo $form->getEtablissement()->nom ?>
        </div>

        <div class="bloc_form etablissement_informations"> 
            <?php include_partial('form_etablissement_defaut', array('etablissement' => $form->getEtablissement(), 'form' => $form[$famille])); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div id="bloc_<?php echo $famille ?>_choice" class="soussigne_form_choice " data-condition-value="<?php echo $famille_autre ?>">
        <h2><?php echo $sous_titre ?></h2>
        <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $famille == "vendeur"): ?>
        <div class="section_label_strong etablissement_famille_choice">
            <?php echo $form[$famille.'_type']->renderLabel() ?>
            <?php echo $form[$famille.'_type']->renderError() ?>
            <?php echo $form[$famille.'_type']->render(array('class' => 'famille', 'data-template' => '#template_url_etablissement', 'data-container' => '#'.$form[$famille.'_identifiant']->renderId())) ?>
        </div>
        <?php endif; ?>
        <div class="section_label_strong etablissement_choice" id="listener_<?php echo $famille ?>_choice">
            <?php echo $form[$famille.'_identifiant']->renderError() ?>
            <label for="">Nom : <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_manquant" title="Message aide"></a></label>
            <?php echo $form[$famille.'_identifiant']->render(array('data-remove-inputs' => '#etablissement_informations_'.$famille, 'data-infos-container' => '#etablissement_informations_'.$famille)) ?>
        </div>

        <div class="bloc_form etablissement_informations" id="etablissement_informations_<?php echo $famille ?>"> 
            <?php include_partial('form_etablissement', array('form' => $form[$famille])); ?>
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
