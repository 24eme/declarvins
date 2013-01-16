<form  class="popup_form" id="form_ajout" action="<?php echo url_for('daids_visualisation_update_cvo', $form->getObject()) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['total_cvo']->renderError() ?></span>
		<?php echo $form['total_cvo']->renderLabel() ?>
		<?php echo $form['total_cvo']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
