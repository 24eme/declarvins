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
						<?php if ($convention = $compte->getConventionCiel()): ?>
				        <?php if($convention->valide): ?>
				        <li><a href="<?php echo url_for("profil_convention", $etablissement) ?>" class=""><span>Convention CIEL</span></a></li>
				        <li><a href="<?php echo url_for("profil_avenant", $etablissement) ?>" class=""><span>Avenant au contrat d'inscription</span></a></li>
				        <?php endif; ?>
				        <?php endif; ?>
			        </ul>
				</div>
			</div>
		<?php endif; ?>
		
		<div id="visualisation_profil">
			<?php include_partial('etablissement', array('etablissement' => $etablissement)); ?>
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