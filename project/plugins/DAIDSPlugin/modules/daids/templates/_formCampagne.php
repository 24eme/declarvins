<form  class="popup_form" id="form_ajout" action="<?php echo url_for('daids_mon_espace', $etablissement) ?>" method="post">
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form->renderGlobalErrors() ?><?php echo $form['campagne']->renderError() ?></span>
		<?php echo $form['campagne']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
