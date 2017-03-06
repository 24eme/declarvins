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
				
				<form action="<?php echo url_for('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>" method="post" id="vrac_condition">
				<?php 
					if ($vrac->hasOioc() && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
					?>
					<div style="background: none repeat scroll 0 0 #ECFEEA;border: 1px solid #359B02;color: #1E5204;font-weight: bold;text-align: left;margin: 10px 0 10px 0;padding: 5px 10px;">
						<ul>
							<li>
								Une fois le contrat validé par toutes les parties, votre déclaration de transaction sera envoyée automatiquement à votre OIOC.<br />Si la date de chargement Oco n'est pas renseignée sur votre contrat en ligne dans les 24 heures ouvrées pour AVPI, 48 heures ouvrées pour QUALISUD, vous devrez impérativement prendre contact avec votre OIOC afin de transmettre vous-même votre déclaration de transaction (« PDF Transaction »).<br />Votre interprofession ne pourra être tenu responsable de la non réception de votre déclaration de transaction par votre OIOC.
							</li>
						</ul>
					</div>
				        		<div class="vracs_ligne_form" style="font-weight: bold;">
				            		<?php echo $form['transaction']->renderError() ?>
				            		<?php echo $form['transaction']->render() ?>
				            		<?php echo $form['transaction']->renderLabel() ?>
				        		</div>
					<?php 
						}
					?>
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
