<section id="contenu">
	<h1>Sélectionnez votre établissement</h1>
	<div id="selection_etablissement">
		<form action="<?php echo url_for('@tiers') ?>" method="post">
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->renderGlobalErrors(); ?>
			<?php echo $form['tiers']->render() ?>
			
			<div class="ligne_form_btn">
				<button class="btn_valider" type="submit">Valider</button>
			</div>
		</form>
	</div>
</section>