<?php include_component('global', 'navTop', array('active' => 'profil')); ?>
<section id="contenu">
	
	<div id="profil">
		<?php if ($hasCompte): ?>
			<div id="formulaire_profil">
				<?php include_partial('form_compte', array('form' => $form, 'etablissement' => $etablissement)); ?>
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