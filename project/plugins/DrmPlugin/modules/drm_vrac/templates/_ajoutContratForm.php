<div id="popup_ajout_contrat_<?php echo $form->getObject()->getIdentifiant() ?>" class="popup_contenu">
	<form  class="popup_form" id="subForm" action="<?php echo url_for('vrac_ajout_contrat', $form->getObject()) ?>" method="post">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<div class="ligne_form">
			<label for="contrat"><?php echo $form['vrac']->renderLabel() ?> </label>
			<?php echo $form['vrac']->render() ?>
			<span class="error"><?php echo $form['vrac']->renderError() ?></span>
		</div>
		<div class="ligne_form_btn">
			<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
			<button name="valider" class="btn_valider" type="submit">Valider</button>
		</div>
	</form>
</div>