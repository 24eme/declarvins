<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques', array('type' => $type)) ?>" method="get">
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form->renderGlobalErrors() ?><?php echo $form['query']->renderError() ?></span>
		<?php echo $form['query']->renderLabel() ?><?php echo $form['query']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button class="btn_valider" type="submit">Filtrer</button>
	</div>
</form>
