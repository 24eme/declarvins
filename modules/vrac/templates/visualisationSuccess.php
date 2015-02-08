<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal"> 
            <div id="recap_saisie" class="popup_form visualisation_contrat">
                <?php if ($sf_user->hasFlash('termine')): ?>
					<h2>La saisie est terminée !</h2>
					<?php if(!$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre contrat a bien été enregistré. Vous allez recevoir un mail de confirmation.<br />
					Il va être envoyé aux autres parties concernées pour validation.<br />
					Vous recevrez une version du contrat en .pdf avec le numéro de contrat (VISA Interprofessionnel) lorsque toutes les parties auront validé le contrat (ou un message d'annulation de contrat en cas de refus / non validation par l'une des parties).<br /><br />
					Le contrat ne sera valable que lorsque vous aurez reçu cette version faisant figurer le numéro de contrat.<br /><br />
					Attention si le contrat n’est pas validé d'ici 10 jours par vos partenaires, il sera automatiquement supprimé et non valable.
					</p>
					<?php endif; ?>
				<?php endif; ?>
            	<?php if ($sf_user->hasFlash('valide')): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre validation a bien été prise en compte.<br />
					Vous recevrez prochainement le contrat validé en pdf (après validation des éventuelles autres parties) ou un message de suppression de contrat (en cas de refus / non validation par l’une des parties).
					</p>
				<?php endif; ?>
            	<?php if ($sf_user->hasFlash('annulation')): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre rejet du contrat a bien été pris en compte.<br />
					L'information sera transmise à toutes les parties concernées.<br />
					Ce contrat est désormais supprimé et considéré comme non valable.
					</p>
				<?php endif; ?>
                <div id="titre">
                    <span class="style_label">N° de Visa du contrat : <?php echo ($vrac->isValide())? $vrac->numero_contrat : 'En attente'; ?></span>
                </div>
                <div id="vrac_condition">  
                    <div class="legende" id="ss_titre">
                        <span class="style_label">Etat du contrat</span>
                    	<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
	                        <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE): ?>
	                        <a href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_SOLDE, 'etablissement' => $etablissement)) ?>" id="solder_contrat">Solder le contrat</a>
	                        <?php elseif ($vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE): ?>
	                        <a href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE, 'etablissement' => $etablissement)) ?>" id="solder_contrat">Désolder le contrat</a>
	                        <?php endif; ?>
                        <?php endif; ?>
                        <div>
                            <span class="statut statut_<?php echo $vrac->getStatutCssClass() ?>"></span><span class="legende_statut_texte"><?php echo $vrac->valide->statut ?></span>
                        </div>                            
                    </div>
                    <?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE) || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
				        
				        <div id="ligne_btn">
				        	<?php if ($vrac->isRectifiable()): ?>
				            <form method="get" action="<?php echo url_for('vrac_rectificative', array('sf_subject' => $vrac, 'etablissement' => $etablissement)) ?>">
				                <button type="submit" id="btn_editer_contrat"  class="modifier" style="font-size: 12px;">Soumettre un contrat rectificatif</button>
				            </form>
				        	<?php endif; ?>
                            <?php if($vrac->isEditable() && $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
	                        <a href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_ANNULE, 'etablissement' => $etablissement)) ?>" id="btn_annuler_contrat" style="font-size: 12px;" onclick="return confirm('Confirmez-vous l\'annulation de ce contrat ?')"> Annuler le contrat</a>
                            <?php endif; ?>  
				        </div>
				    <?php endif; ?>
                </div>
            
                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>
                
    
			    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $vrac->isModifiable()): ?>
			        <div class="ligne_form_btn" style="margin: 0;">
			            <form method="get" action="<?php echo url_for('vrac_modificative', $vrac) ?>">
			                <button type="submit" id="btn_editer_contrat"  class="modifier" style="font-size:12px; float: none;">Soumettre un contrat modificatif</button>
						</form>
			        </div>
			    <?php endif; ?>
    
                <div class="ligne_form_btn">
					<?php if ($etablissement): ?>
						<a href="<?php echo url_for("vrac_etablissement", array('identifiant' => $etablissement->identifiant)) ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
					<?php else: ?>
						<a href="<?php echo url_for("vrac_admin") ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
					<?php endif; ?>
					<?php if ($vrac->isValide()): ?>
					<a class="valider_etape" href="<?php echo url_for('vrac_pdf', array('sf_subject' => $vrac, 'etablissement' => $etablissement)) ?>"><span>PDF Contrat</span></a>
					<?php if ($vrac->has_transaction): ?>
					<a class="valider_etape" href="<?php echo url_for('vrac_pdf_transaction', array('sf_subject' => $vrac, 'etablissement' => $etablissement)) ?>"><span>PDF Transaction</span></a>
					<?php endif; ?>
					<?php endif; ?>
				</div>
                
            </div> 
        </section>
    </div>
</div>
