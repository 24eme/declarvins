<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_crd_product_ajout', $form->getDRM()) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['categorie']->renderError() ?></span>
		<?php echo $form['categorie']->renderLabel() ?>
		<?php echo $form['categorie']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['type']->renderError() ?></span>
		<?php echo $form['type']->renderLabel() ?>
		<?php echo $form['type']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['centilisation']->renderError() ?></span>
		<?php echo $form['centilisation']->renderLabel() ?>
		<?php echo $form['centilisation']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>	