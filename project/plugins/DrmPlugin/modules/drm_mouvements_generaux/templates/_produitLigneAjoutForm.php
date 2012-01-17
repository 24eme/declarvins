<div id="popup_ajout_produit_<?php echo $certification ?>" class="popup_contenu">
	<form  class="popup_form" id="subForm" action="<?php echo url_for('@drm_mouvements_generaux_product_form') ?>" method="post">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<input type="hidden" name="certification" value="<?php echo $certification ?>" />
		<div class="ligne_form">
			<label for="produit_appellation"><?php echo $form['appellation']->renderLabel() ?> </label>
			<?php echo $form['appellation']->render() ?>
			<span class="error"><?php echo $form['appellation']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<label for="produit_couleur"><?php echo $form['couleur']->renderLabel() ?> </label>
			<?php echo $form['couleur']->render() ?>
			<span class="error"><?php echo $form['couleur']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<label for="produit_label"><?php echo $form['label']->renderLabel() ?> </label>
			<div style="width: 240px; height: 100px; display: inline-block; overflow-x: hidden; overflow-y: scroll;">
				<?php echo $form['label']->render(array('class' => 'select_multiple')) ?>
			</div>
			<span class="error"><?php echo $form['label']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<label for="produit_label_supplementaire"><?php echo $form['label_supplementaire']->renderLabel() ?> </label>
			<?php echo $form['label_supplementaire']->render() ?>
			<span class="error"><?php echo $form['label_supplementaire']->renderError() ?></span>
		</div>
		<div class="ligne_form_btn">
			<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
			<button name="valider" class="btn_valider" type="submit">Valider</button>
		</div>
	</form>
</div>