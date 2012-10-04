<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
                <div id="titre">
                    <span class="style_label">N° d'enregistrement du contrat : <?php echo $vrac->numero_contrat ?></span>
                </div>
            
                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>
                
                <div class="ligne_form_btn">
					<?php if (!$vrac->isValide()): ?>
                	<form action="<?php echo url_for('vrac_validation', array('sf_subject' => $vrac, 'acteur' => $acteur)) ?>" method="post" id="vrac_condition">
						<?php echo $form->renderHiddenFields() ?>
						<?php echo $form->renderGlobalErrors() ?>
						<input class="valider_etape" type="submit" value="Valider"  />
					</form>
					<?php endif; ?>
				</div>
                
            </div> 
        </section>
    </div>
</div>
