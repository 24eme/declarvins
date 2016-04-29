<form  class="popup_form" id="form_ajout" action="<?php echo url_for('drm_recap_es_detail', $detail) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['volume']->renderError() ?></span>
		<?php echo $form['volume']->renderLabel() ?>
		<?php echo $form['volume']->render() ?>
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['mois']->renderError() ?></span>
		<?php echo $form['mois']->renderLabel() ?>
		<?php echo $form['mois']->render() ?>
		
	</div>
	<div class="ligne_form">
		<span class="error"><?php echo $form['annee']->renderError() ?></span>
		<?php echo $form['annee']->renderLabel() ?>
		<?php echo $form['annee']->render() ?>
		
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
