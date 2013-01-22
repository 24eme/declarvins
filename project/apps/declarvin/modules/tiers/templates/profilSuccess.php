<?php include_component('global', 'navTop', array('active' => 'profil')); ?>
<section id="contenu">
<?php if ($hasCompte): ?>
	<?php include_partial('form_compte', array('form' => $form, 'etablissement' => $etablissement)); ?>
<?php endif; ?>

<?php include_partial('etablissement', array('etablissement' => $etablissement)); ?>
<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
<a href="<?php echo url_for('profil_statut', $etablissement) ?>" id="">Archiver l'etablissement</a>
<?php endif; ?>
</section>