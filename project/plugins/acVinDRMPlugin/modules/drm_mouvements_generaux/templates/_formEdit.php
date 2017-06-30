<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_mouvements_generaux_product_edit', $detail) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['libelle']->renderError() ?></span>
		<?php echo $form['libelle']->renderLabel() ?>
		<?php echo $form['libelle']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
<style>
.popup_form .ligne_form ul.radio_list {
	margin-left: 105px !important;
}
.popup_form .ligne_form label {
	width: 100px !important;
}
.popup_form .ligne_form ul.radio_list label {
	width: auto !important;
}
.popup_form .ligne_form input[type="text"] {
    width: 345px !important;
}
</style>
