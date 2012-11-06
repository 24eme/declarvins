<section id="contenu">
	<h1>Sélectionnez votre établissement</h1>
	<div id="creation_compte">
		<form action="<?php echo url_for('@tiers') ?>" method="post">
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->renderGlobalErrors(); ?>
			<?php echo $form['tiers']->render(array('onchange' => 'submit()')) ?>
		</form>
	</div>
</section>