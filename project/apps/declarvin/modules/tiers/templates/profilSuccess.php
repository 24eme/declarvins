<?php include_component('global', 'navTop', array('active' => 'profil')); ?>
<section id="contenu">
<?php if ($hasCompte): ?>
	<?php include_partial('form_compte', array('form' => $form, 'etablissement' => $etablissement)); ?>
<?php else: ?>
	<?php include_partial('etablissement', array('etablissement' => $etablissement)); ?>
<?php endif; ?>
</section>