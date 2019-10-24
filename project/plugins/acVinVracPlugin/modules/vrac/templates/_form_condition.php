    <form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
        
        <div>
        	<h1>Spécificités du contrat</h1>
            <?php if (isset($form['premiere_mise_en_marche'])): ?>
        	<div class="section_label_strong">
            	<?php echo $form['premiere_mise_en_marche']->renderError() ?>
            	<?php echo $form['premiere_mise_en_marche']->renderLabel() ?>
            	<?php echo $form['premiere_mise_en_marche']->render() ?>
        	</div>
        	<?php endif; ?>
			<?php if (isset($form['cas_particulier'])): ?>
            <div class="section_label_strong_bloc">
                <?php echo $form['cas_particulier']->renderError() ?>
                <?php echo $form['cas_particulier']->renderLabel() ?>
                <a class="msg_aide" title="Message aide" data-msg="help_popup_vrac_condition_particuliere" href=""></a>
                <?php echo $form['cas_particulier']->render() ?>
            </div>
            <?php endif; ?>
            <?php if (isset($form['bailleur_metayer'])): ?>
            <div class="section_label_strong">
                <?php echo $form['bailleur_metayer']->renderError() ?>
                <?php echo $form['bailleur_metayer']->renderLabel() ?>
                <?php echo $form['bailleur_metayer']->render() ?>
            </div>
            <?php endif; ?>
        	<div class="section_label_strong">
            	<?php echo $form['export']->renderError() ?>
            	<?php echo $form['export']->renderLabel() ?>
            	<?php echo $form['export']->render() ?>
        	</div>
            <?php if(isset($form['annexe'])): ?>
            <div  class="section_label_strong">
                <?php echo $form['annexe']->renderError() ?>
                <?php echo $form['annexe']->renderLabel() ?>
                <?php echo $form['annexe']->render() ?>
            </div>
            <?php endif; ?>
            <?php if(isset($form['reference_contrat_pluriannuel'])): ?>
            <div class="section_label_strong">
                <?php echo $form['reference_contrat_pluriannuel']->renderError() ?>
                <?php echo $form['reference_contrat_pluriannuel']->renderLabel() ?>
                <?php echo $form['reference_contrat_pluriannuel']->render() ?>
            </div>
            <?php endif; ?>
        </div>
        <h1>Retiraison / Enlèvement</h1>
        <div class="section_label_strong">
            <?php echo $form['type_retiraison']->renderError() ?>
            <?php echo $form['type_retiraison']->renderLabel() ?>
            <?php echo $form['type_retiraison']->render() ?>
        </div>
        <?php if (!$form->conditionneIVSE()): ?>
        <div class="section_label_strong">
            <?php echo $form['vin_livre']->renderError() ?>
            <?php echo $form['vin_livre']->renderLabel() ?>
            <?php echo $form['vin_livre']->render() ?>
        </div>
        <?php endif; ?>
            <?php if(isset($form['date_debut_retiraison'])): ?>
            <div class="section_label_strong">
                <?php echo $form['date_debut_retiraison']->renderError() ?>
                <?php echo $form['date_debut_retiraison']->renderLabel() ?>
                <?php echo $form['date_debut_retiraison']->render(array('class' => 'datepicker')) ?>
                &nbsp;(jj/mm/aaaa)
            </div>
            <?php endif; ?>
            <div class="section_label_strong">
                <?php echo $form['date_limite_retiraison']->renderError() ?>
                <?php echo $form['date_limite_retiraison']->renderLabel() ?>
                <?php echo $form['date_limite_retiraison']->render(array('class' => 'datepicker')) ?>
                &nbsp;(jj/mm/aaaa)
            </div>
            <?php if(isset($form['clause_reserve_retiraison'])): ?>
            <div class="section_label_strong">
                <?php echo $form['clause_reserve_retiraison']->renderError() ?>
                <?php echo $form['clause_reserve_retiraison']->renderLabel() ?>
                <?php echo $form['clause_reserve_retiraison']->render() ?>
            </div>
            <?php endif; ?>
        	<?php if ($form->conditionneIVSE()): ?>
        	<p>En cas de calendrier de retiraison, indiquez les échéances dans la case &laquo;commentaires&raquo; de l'étape suivante</p>
        	
        	<?php endif; ?>
        	
            <?php if (isset($form['has_transaction'])): ?>
            <h1>Transaction</h1>
            <div>
                <?php echo $form['has_transaction']->renderError() ?>
                <?php echo $form['has_transaction']->render() ?> 
                <?php echo $form['has_transaction']->renderLabel() ?>
            </div>
            <?php endif; ?>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'produit', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
            <button class="valider_etape" type="submit"><span>Etape Suivante</span></button>
        </div>
        
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('<?php if ($form->getObject()->hasVersion()): ?>Attention, vous êtes sur le point d\'annuler les modifications en cours<?php else: ?>Attention, ce contrat sera supprimé de la base<?php endif; ?>')"><span><?php if($form->getObject()->hasVersion()): ?>Annuler les modifications<?php else: ?>supprimer le contrat<?php endif; ?></span></a>
        </div> 
    </form>
