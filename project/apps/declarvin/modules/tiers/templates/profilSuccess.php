<?php include_component('global', 'navTop', array('active' => 'profil')); ?>
<section id="contenu">
	
	<div id="profil">
		<?php if ($hasCompte): ?>
			<div id="formulaire_profil">
				<?php include_partial('form_compte', array('compte' => $compte, 'form' => $form, 'etablissement' => $etablissement)); ?>
				<div style="padding: 10px 0;" class="clearfix">
					<h1>Vos documents</h1>
					<ul>
						<li><a href="<?php echo url_for("profil_pdf", $etablissement) ?>" class=""><span>Contrat d'inscription</span></a></li>
						<li><a href="<?php echo url_for("fiche_pdf", $etablissement) ?>" class=""><span>Fiche profil</span></a></li>
						<?php if ($convention = $compte->getConventionCiel()): ?>
				        <?php if($convention->valide && $etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)): ?>
				        <li><a href="<?php echo url_for("profil_convention", $etablissement) ?>" class=""><span>Convention CIEL</span></a></li>
				        <?php endif; ?>
				        <?php endif; ?>
			        </ul>
				</div>
				<?php if ($societe && !$societe->isNew()): ?>
				<div style="padding: 10px 0;" class="clearfix">
					<h1>Vos informations de facturation</h1>
					<ul>
						<li style="margin: 10px 0;">Email : <strong><?php echo $societe->email ?></strong></li>
						<li style="margin: 10px 0;">Adresse :</li>
						<li style="margin: 10px 0;"><strong><?php echo $societe->siege->adresse ?></strong></li>
						<li style="margin: 10px 0;"><strong><?php echo $societe->siege->code_postal ?> <?php echo $societe->siege->commune ?></strong></li>

						<?php if ($sepa = $societe->getMandatSepa($sf_user->getCompte()->getGerantInterpro()->_id)): ?>
						<li style="margin: 10px 0;">Paiement par prélèvement automatique :</li>
						<li style="margin: 10px 0;">Banque <strong><?php echo $sepa->getBanqueNom() ?></strong></li>
						<li style="margin: 10px 0;">Compte <strong><?php echo $sepa->getIbanFormate() ?></strong></li>
						<?php endif; ?>
			    </ul>
				</div>
    		    <?php endif; ?>


    			<?php if ($formSociete): ?>
    			<form method="post" action="<?php echo url_for('profil', $etablissement); ?>">
    				<?php echo $formSociete->renderHiddenFields(); ?>
    				<?php echo $formSociete->renderGlobalErrors(); ?>

                <div class="ligne_form">
                            <label>&nbsp;</label>
                            <?php echo $formSociete['code_comptable_client']->renderError() ?>
                </div>
    			<div class="ligne_form">
    					<label>Code comptable société :</label>
    					<?php echo $formSociete['code_comptable_client']->render(array('style' => 'width: 120px;text-align:right;')) ?>
    					<input type="submit" value="Modifier"/>
    			</div>
    			</form>
    			<?php endif; ?>

			</div>
			<?php endif; ?>

		<div id="visualisation_profil">
			<?php include_partial('etablissement', array('etablissement' => $etablissement, 'formEtablissement' => $formEtablissement, 'formSociete' => $formSociete)); ?>
		</div>

		<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
		<?php if ($etablissement->statut == Etablissement::STATUT_ARCHIVE): ?>
			<a href="<?php echo url_for('profil_statut', $etablissement) ?>" id="btn_archiver_etablissement" class="btn_violet">Activer l'etablissement</a>
		<?php else: ?>
			<a href="<?php echo url_for('profil_statut', $etablissement) ?>" id="btn_archiver_etablissement" class="btn_violet">Archiver l'etablissement</a>
		<?php endif; ?>
		<?php endif; ?>
	</div>

</section>