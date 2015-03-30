<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
            	<?php if ($sf_user->hasFlash('annulation')): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre refus du contrat a bien été prise en compte.
					</p>
				<?php endif; ?>
                <div id="titre">
                    <span class="style_label">N° de Visa du contrat : <?php echo ($vrac->isValide())? $vrac->numero_contrat : 'En attente'; ?></span>
                </div>
            	<br />
                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>
                
				
		   		
				<?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION && !$dateAnnulationActeur): ?>
				<form action="<?php echo url_for('vrac_annulation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>" method="post" id="vrac_condition">
					 <div class="ligne_form_btn">
						<a class="annuler_saisie" onclick="return confirm('Confirmez-vous le refus de l'\annulation de ce contrat?')" href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE, 'etablissement' => $etablissement)) ?>" id="btn_annuler_contrat">Refuser l'annulation</a>
						<?php echo $form->renderHiddenFields() ?>
						<?php echo $form->renderGlobalErrors() ?>
						 <button class="valider_etape" type="submit"><span>Valider l'annulation</span></button>
					</div>
				</form>
				<?php endif; ?>
				<?php if ($dateAnnulationActeur): ?>
				<div class="ligne_form_btn">
					<p>Vous avez refusé ce contrat le <?php echo strftime('%d/%m/%Y à %Hh%M', strtotime($dateAnnulationActeur)); ?></p>
				</div>
				<?php endif; ?>
                
            </div> 
        </section>
    </div>
</div>
