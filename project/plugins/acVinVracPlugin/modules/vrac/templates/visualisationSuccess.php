<?php use_helper('Date') ?>
<?php use_helper('Vrac'); ?>
<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>
<?php
$rectif = $vrac->generateRectificative();
$rectif->constructId();
$modif = $vrac->generateModificative();
$modif->constructId();
$hasNextVersion = false;
$nextRectif = VracClient::getInstance()->find($rectif->_id);
$nextModif = VracClient::getInstance()->find($modif->_id);
if ($nextRectif && $nextRectif->valide->statut != VracClient::STATUS_CONTRAT_ANNULE) {
    $hasNextVersion = true;
    $nextversion_url = url_for("vrac_visualisation", array('contrat' => str_replace('VRAC-', '', $rectif->_id), 'etablissement' => $etablissement));
}
if ($nextModif && $nextModif->valide->statut != VracClient::STATUS_CONTRAT_ANNULE) {
    $hasNextVersion = true;
    $nextversion_url = url_for("vrac_visualisation", array('contrat' => str_replace('VRAC-', '', $modif->_id), 'etablissement' => $etablissement));
}
?>
<div id="contenu" class="vracs">
    <div id="rub_contrats">
        <section id="principal">
            <div class="ligne_form_btn" style="margin:0 0 30px 0;text-align:left;">
                <?php if ($etablissement): ?>
                    <a style="float:none;" href="<?php echo url_for("vrac_etablissement", array('identifiant' => $etablissement->identifiant)) ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
                <?php else: ?>
                    <a style="float:none;" href="<?php echo url_for("vrac_admin") ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
                <?php endif; ?>
            </div>
            <?php include_component('vrac', 'ongletsPluriannuel', ['vrac' => $vrac, 'etablissement' => $etablissement]) ?>
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
					Votre confirmation d'annulation a bien été prise en compte.
					</p>
				<?php endif; ?>
            	<?php if ($sf_user->hasFlash('attente_annulation')): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Votre demande d'annulation du contrat a bien été prise en compte.<br />
					Elle va être envoyée aux autres parties concernées pour acceptation.<br />
					En l'absence de validation sous trois jours, la demande d'annulation sera automatiquement rejetée.
					</p>
				<?php endif; ?>
            	<?php if ($sf_user->hasFlash('refus_annulation')): ?>
					<p id="titre" style="text-align: left; margin-bottom: 30px;">
					Vous avez refusé la demande d'annulation du contrat.<br />
					Celui-ci reste donc effectif et valable.'
					</p>
				<?php endif; ?>
                <?php if(($vrac->isPluriannuel()||$vrac->isAdossePluriannuel()) && $configurationVrac->isContratPluriannuelActif()): ?>
                <div class="titre" style="background-color: #fff; margin: 10px 0;">
                    <span class="style_label" style="text-align: center; background: url('/images/pictos/pi_pluriannuel.png') left 0 no-repeat;padding: 0px 10px 0 20px;min-height:21px;display:inline-block;vertical-align: middle;">
                        Contrat <?php if($vrac->isAdossePluriannuel()): ?>adossé au contrat pluriannuel cadre n°<a href="<?php echo url_for('vrac_visualisation', array('contrat' => $vrac->reference_contrat_pluriannuel)) ?>"><?php echo $vrac->reference_contrat_pluriannuel ?></a><?php else: ?>pluriannuel cadre<?php endif; ?>
                    </span>
                    <?php if($vrac->isPluriannuel()): ?>
                    <a href="<?php echo url_for('vrac_pluriannuel', array('contrat' => $vrac->_id, 'identifiant' => ($etablissement)? $etablissement->identifiant : VracRoute::ETABLISSEMENT_IDENTIFIANT_ADMIN)) ?>" id="btn_editer_contrat"  class="modifier" style="font-size: 12px;">Créer un contrat d'application</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div id="titre">
                    <span class="style_label">N° de Visa du contrat : <?php echo ($vrac->isValide())? $vrac->numero_contrat : 'En attente'; ?></span><span class="pull-right"><?php $libelles = Vrac::getModeDeSaisieLibelles(); if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)&&$vrac->mode_de_saisie): echo $libelles[$vrac->mode_de_saisie]; endif; ?></span>
                    <?php if ($vrac->oioc->date_traitement): ?>
                    <br />Envoi Oco : <?php echo format_date($vrac->oioc->date_traitement, 'dd/MM/y') ?>
                    <?php endif; ?>
                    <?php if ($vrac->oioc->date_reception): ?>
                    <br />Chargement Oco : <?php echo format_date($vrac->oioc->date_reception, 'dd/MM/y') ?>
                    <?php endif; ?>
                </div>
                <div id="vrac_condition">
                    <div class="legende" id="ss_titre">
                        <span class="style_label">Etat du contrat</span>
                    	<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
	                        <?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE): ?>
	                        <a href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_SOLDE, 'etablissement' => $etablissement)) ?>" id="solder_contrat">Solder le contrat</a>
                            <?php elseif ($vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE && !$vrac->isPluriannuel()): ?>
	                        <a href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE, 'etablissement' => $etablissement)) ?>" id="solder_contrat">Désolder le contrat</a>
	                        <?php endif; ?>
                        <?php endif; ?>
                        <div>
                            <span class="statut <?php echo statusColor($vrac->valide->statut) ?>"></span><span class="legende_statut_texte"><?php echo statusLibelle($vrac->valide->statut, $vrac->isPluriannuel()) ?></span>
                            <?php if ($vrac->valide->commentaire_refus): ?>
                                <div>
                                    <p><?php echo nl2br($vrac->valide->commentaire_refus); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (($etablissement && $etablissement->statut != Etablissement::STATUT_ARCHIVE) || $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>

				        <div id="ligne_btn">
				        	<?php if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE): ?>
				        		<?php if($hasNextVersion): ?>
				        			<a href="<?php echo $nextversion_url; ?>" id="btn_editer_contrat"  class="modifier" style="font-size: 12px; background-color: #E42C2C; border-color: #E42C2C;">Une version du contrat est en cours</a>
				        		<?php else: ?>
				            <form method="get" action="<?php echo url_for('vrac_rectificative', array('sf_subject' => $vrac, 'etablissement' => $etablissement)) ?>">
				                <button type="submit" id="btn_editer_contrat"  class="modifier" style="font-size: 12px;">Soumettre un contrat rectificatif</button>
				            </form>
				            	<?php endif; ?>
				            <?php endif; ?>
                            <?php if($vrac->isEditable() && !$hasNextVersion): ?>
	                        <a href="<?php echo url_for('vrac_statut', array('sf_subject' => $vrac, 'statut' => VracClient::STATUS_CONTRAT_ANNULE, 'etablissement' => $etablissement)) ?>" id="btn_annuler_contrat" style="font-size: 12px;" onclick="return confirm('Confirmez-vous l\'annulation de ce contrat ?')"> Annuler le contrat</a>
                            <?php endif; ?>
				        </div>
				    <?php endif; ?>
                </div>

                <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac, 'editer_etape' => false)); ?>


			    <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && ($vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE)): ?>
			        <div class="ligne_form_btn" style="margin: 0;">

				        		<?php if($hasNextVersion): ?>
				        			<a id="btn_editer_contrat" href="<?php echo $nextversion_url; ?>" class="modifier" style="font-size: 12px; float: none; background-color: #E42C2C; border-color: #E42C2C;">Une version du contrat est en cours</a>
				        		<?php else: ?>
			            <form method="get" action="<?php echo url_for('vrac_modificative', $vrac) ?>">
			                <button type="submit" id="btn_editer_contrat"  class="modifier" style="font-size:12px; float: none;">Soumettre un contrat modificatif</button>
						</form>

			    			<?php endif; ?>
			        </div>
			    <?php endif; ?>

                <div class="ligne_form_btn">
					<?php if ($etablissement): ?>
						<a href="<?php echo url_for("vrac_etablissement", array('identifiant' => $etablissement->identifiant)) ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
					<?php else: ?>
						<a href="<?php echo url_for("vrac_admin") ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
					<?php endif; ?>
					<?php if ($vrac->isValide() && $vrac->valide->statut != VracClient::STATUS_CONTRAT_ANNULE): ?>
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
