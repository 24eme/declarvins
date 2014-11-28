<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
            	<?php if ($sf_user->hasFlash('termine')): ?>
					<h2>La saisie est terminée !</h2>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre contrat a bien été enregistré. Vous allez recevoir un mail de confirmation.<br />
					Il va être envoyé aux autres parties concernées pour validation.<br />
					Vous recevrez une version du contrat en .pdf avec le numéro de contrat (VISA Interprofessionnel) lorsque toutes les parties auront validé le contrat (ou un message d'annulation de contrat en cas de refus / non validation par l'une des parties).<br /><br />
					Le contrat ne sera valable que lorsque vous aurez reçu cette version faisant figurer le numéro de contrat.<br /><br />
					Attention si le contrat n’est pas validé d'ici 10 jours par vos partenaires, il sera automatiquement supprimé et non valable.
					</p>
				<?php endif; ?>
            	<?php if ($sf_user->hasFlash('valide')): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre validation a bien été prise en compte.<br />
					Vous recevrez prochainement le contrat validé en pdf (après validation des éventuelles autres parties) ou un message de suppression de contrat (en cas de refus / non validation par l’une des parties).
					</p>
				<?php endif; ?>
                <div id="titre">
                    <span class="style_label">N° de Visa du contrat : <?php echo ($vrac->isValide())? $vrac->numero_contrat : 'En attente'; ?></span>
                </div>
            	<br />
                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>
                
				
		   		
				<?php if (!$vrac->isValide() && !$dateValidationActeur): ?>
				<?php if ($vrac->has_transaction): ?>
				<p style="text-align:right;">Assurez-vous de bien respecter les délais minimum de transmission de vos déclarations de transactions à votre organisme d’inspection/contrôle.</p>
				<?php endif; ?>
				<form action="<?php echo url_for('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>" method="post" id="vrac_condition">
					 <div class="ligne_form_btn">
						<a class="annuler_saisie" onclick="return confirm('Confirmez-vous le refus du contrat?')" href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_ANNULE, 'etablissement' => $etablissement)) ?>" id="btn_annuler_contrat">Refuser</a>
						<?php echo $form->renderHiddenFields() ?>
						<?php echo $form->renderGlobalErrors() ?>
						 <button class="valider_etape" type="submit"><span>Valider - Signature</span></button>
					</div>
				</form>
				<?php endif; ?>
				<?php if ($dateValidationActeur): ?>
				<div class="ligne_form_btn">
					<p>Vous avez validé ce contrat le <?php echo strftime('%d/%m/%Y à %Hh%M', strtotime($dateValidationActeur)); ?></p>
				</div>
				<?php endif; ?>
                
            </div> 
        </section>
    </div>
</div>
