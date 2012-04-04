<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_declaratif_frequence_form', $form->getDrm()) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['frequence']->renderError() ?></span>
		<?php echo $form['frequence']->renderLabel() ?>
		<?php echo $form['frequence']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>