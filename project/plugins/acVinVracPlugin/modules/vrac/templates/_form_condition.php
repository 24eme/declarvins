    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <div>
        	<h1>Type</h1>
            <?php if (isset($form['type_transaction'])): ?>
            <div class="section_label_strong bloc_condition" data-condition-cible="#bloc_ramasseur_raisin">
                <?php echo $form['type_transaction']->renderError() ?>
                <?php echo $form['type_transaction']->renderLabel() ?>
                <?php echo $form['type_transaction']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['ramasseur_raisin'])): ?>
            <div class="section_label_strong bloc_conditionner" id="bloc_ramasseur_raisin" data-condition-value="raisin">
                <?php echo $form['ramasseur_raisin']->renderError() ?>
                <?php echo $form['ramasseur_raisin']->renderLabel() ?>
                <?php echo $form['ramasseur_raisin']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['is_contrat_pluriannuel'])): ?>
            <div  id="vrac_type_contrat" class="section_label_strong bloc_condition" data-condition-cible="#bloc_reference_pluriannuel">
                <?php echo $form['is_contrat_pluriannuel']->renderError() ?>
                <?php echo $form['is_contrat_pluriannuel']->renderLabel() ?>
                <?php echo $form['is_contrat_pluriannuel']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['reference_contrat_pluriannuel'])): ?>
            <div class="section_label_strong bloc_conditionner" id="bloc_reference_pluriannuel" data-condition-value="1">
                <?php echo $form['reference_contrat_pluriannuel']->renderError() ?>
                <?php echo $form['reference_contrat_pluriannuel']->renderLabel() ?>
                <?php echo $form['reference_contrat_pluriannuel']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['pluriannuel_campagne_debut'])&&isset($form['pluriannuel_campagne_fin'])): ?>
            <div class="section_label_strong">
                <?php echo $form['pluriannuel_campagne_debut']->renderError() ?>
                <?php echo $form['pluriannuel_campagne_debut']->renderLabel() ?>
                <?php echo $form['pluriannuel_campagne_debut']->render() ?>
            </div>
            <div class="section_label_strong">
                <?php echo $form['pluriannuel_campagne_fin']->renderError() ?>
                <?php echo $form['pluriannuel_campagne_fin']->renderLabel() ?>
                <?php echo $form['pluriannuel_campagne_fin']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['campagne_courante'])): ?>
            <div class="section_label_strong">
                <?php echo $form['campagne_courante']->renderError() ?>
                <?php echo $form['campagne_courante']->renderLabel() ?>
                <?php echo $form['campagne_courante']->render() ?>
            </div>
            <?php endif; ?>
        	<h1>Spécificités</h1>
			<?php if (isset($form['cas_particulier'])): ?>
            <div class="section_label_strong_bloc">
                <?php echo $form['cas_particulier']->renderError() ?>
                <?php echo $form['cas_particulier']->renderLabel() ?>
                <a class="msg_aide" title="Message aide" data-msg="help_popup_vrac_condition_particuliere" href=""></a>
                <?php echo $form['cas_particulier']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['premiere_mise_en_marche'])): ?>
        	<div class="section_label_strong">
            	<?php echo $form['premiere_mise_en_marche']->renderError() ?>
            	<?php echo $form['premiere_mise_en_marche']->renderLabel() ?>
            	<?php echo $form['premiere_mise_en_marche']->render() ?>
        	</div>
        	<?php endif; ?>
        	<div class="section_label_strong">
            	<?php echo $form['export']->renderError() ?>
            	<?php echo $form['export']->renderLabel() ?>
            	<?php echo $form['export']->render() ?>
        	</div>
            <?php if (isset($form['bailleur_metayer'])): ?>
            <div class="section_label_strong">
                <?php echo $form['bailleur_metayer']->renderError() ?>
                <?php echo $form['bailleur_metayer']->renderLabel() ?>
                <?php echo $form['bailleur_metayer']->render() ?>
            </div>
            <?php endif; ?>
            <?php if(isset($form['annexe'])): ?>
            <div  class="section_label_strong">
                <?php echo $form['annexe']->renderError() ?>
                <?php echo $form['annexe']->renderLabel() ?>
                <?php echo $form['annexe']->render() ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'produit', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>

        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('<?php if ($form->getObject()->hasVersion()): ?>Attention, vous êtes sur le point d\'annuler les modifications en cours<?php else: ?>Attention, ce contrat sera supprimé de la base<?php endif; ?>')"><span><?php if($form->getObject()->hasVersion()): ?>Annuler les modifications<?php else: ?>supprimer le contrat<?php endif; ?></span></a>
        </div>
    </form>
