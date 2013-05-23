<form  class="popup_form" id="form_ajout" action="<?php echo url_for('statistiques_bilan_drm') ?>" method="get">
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form->renderGlobalErrors() ?><?php echo $form['campagne']->renderError() ?></span>
		<label>Campagne : </label><?php echo $form['campagne']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button name="valider" class="btn_valider" type="submit" value="true">Filtrer</button>
	</div>
</form>
