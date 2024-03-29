<form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement, 'pluriannuel' => $pluriannuel)) ?>">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>

    <?php if(isset($form['vous_etes']) && (!$form->getObject()->hasVersion() || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))): ?>
    
    <div id="bloc_vous_etes" class="contenu_onglet bloc_condition" data-condition-cible="#bloc_acheteur_choice|#bloc_vendeur_choice|#bloc_acheteur_vous|#bloc_vendeur_vous">
        <?php echo $form['vous_etes']->renderError(); ?>
        <?php echo $form['vous_etes']->renderLabel(); ?>
        <?php echo $form['vous_etes']->render(); ?>
    </div>
    <?php endif; ?>
    
    
    <?php if ($form->getObject()->hasVersion() && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    	<?php include_partial('vrac/form_soussignes_version', array('vrac' => $form->getObject(), 'form' => $form)) ?>
    <?php else: ?>

    <?php include_partial('vrac/form_soussigne_item', array('form' => $form,
                                                            'titre' => 'Vendeur',
                                                            'famille' => 'vendeur', 
                                                            'famille_autre' => 'acheteur', 
                                                            'sous_titre' => 'Séléctionner un vendeur',
                                                            'sous_titre_vous' => "Vous êtes le vendeur",
                                                            'field_adresse' => 'adresse_stockage',
                                                            'label_adresse' => 'Adresse de stockage différente')) ?>

    
    <?php include_partial('vrac/form_soussigne_item', array('form' => $form,
                                                            'titre' => 'Acheteur',
                                                            'famille' => 'acheteur', 
                                                            'famille_autre' => 'vendeur', 
                                                            'sous_titre' => 'Séléctionner un acheteur',
                                                            'sous_titre_vous' => "Vous êtes l'acheteur",
                                                            'field_adresse' => 'adresse_livraison',
                                                            'label_adresse' => 'Adresse de livraison différente')) ?>

    <?php include_partial('vrac/form_soussigne_item_mandataire', array('form' => $form)) ?>
    
    <?php endif; ?>

    <div class="ligne_form_btn">
        <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('<?php if ($form->getObject()->hasVersion()): ?>Attention, vous êtes sur le point d\'annuler les modifications en cours<?php else: ?>Attention, ce contrat sera supprimé de la base<?php endif; ?>')"><span><?php if($form->getObject()->hasVersion()): ?>Annuler les modifications<?php else: ?>supprimer le contrat<?php endif; ?></span></a>
        <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
    </div>
    
</form>
<?php include_partial('url_etablissement_template', array('interpro' => $form->getInterpro(), 'etablissement' => $etablissement)); ?>
<?php include_partial('url_informations_template', array('vrac' => $form->getObject(), 'etablissement' => $etablissement, 'etape' => $etape)); ?>

