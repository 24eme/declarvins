<?php include_component('global', 'navTop', array('active' => 'profil')); ?>
<section id="contenu">
	
	<div id="profil">
		<?php if ($hasCompte): ?>
			<?php include_partial('form_compte', array('form' => $form, 'etablissement' => $etablissement)); ?>
		<?php endif; ?>

		<?php include_partial('etablissement', array('etablissement' => $etablissement)); ?>

		<a href="<?php echo url_for('profil_statut', $etablissement) ?>" id="btn_archiver_etablissement" class="btn_violet">Archiver l'etablissement</a>
	</div>

</section>