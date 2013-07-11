<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
            	<?php if ($sf_user->hasFlash('termine')): ?>
					<h2>La saisie est terminée !</h2>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre contrat a bien été enregistré.<br />
					Vous recevrez une version du contrat en .pdf avec le numéro de contrat lorsque toutes les parties auront validé le contrat.<br />
					Le contrat ne pourra être considéré comme valable que lorsque vous aurez reçu cette version faisant figurer le numéro de contrat.<br /><br />
					Attention si le contrat n’est pas validé d'ici 10 jours par vos partenaires, il sera automatiquement supprimé et non valable.
					</p>
				<?php endif; ?>
                <div id="titre">
                    <span class="style_label">N° de Visa du contrat : <?php echo ($vrac->isValide())? $vrac->numero_contrat : 'En attente'; ?></span>
                </div>
            	<br />
                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>
                
				
		   		
				<?php if (!$vrac->isValide() && !$dateValidationActeur): ?>
				<p style="text-align:right;">Assurez-vous de bien respecter les délais minimum de transmission de vos déclarations de transactions à votre organisme d’inspection/contrôle.</p>
				<form action="<?php echo url_for('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur)) ?>" method="post" id="vrac_condition">
					 <div class="ligne_form_btn">
						<a class="annuler_saisie" onclick="return confirm('Confirmez-vous la suppression du contrat?')" href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $vrac, 'etablissement' => $etablissement)) ?>" id="btn_annuler_contrat">Refuser</a>
						<?php echo $form->renderHiddenFields() ?>
						<?php echo $form->renderGlobalErrors() ?>
						 <button class="valider_etape" type="submit"><span>Valider</span></button>
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
