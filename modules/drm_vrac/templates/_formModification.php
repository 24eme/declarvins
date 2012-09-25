<form class="popup_form" id="subForm" action="<?php echo url_for('drm_vrac_update_volume', $form->getObject()) ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<p><?php echo $form->getObject()->getKey() ?> (<?php echo $contrat->volume_propose; ?>&nbsp;hl à <?php echo $contrat->prix_unitaire; ?>&nbsp;€/hl)<br /><?php if ($contrat->acheteur->raison_sociale) { echo $contrat->acheteur->raison_sociale; if ($contrat->acheteur->nom) { echo ' ('.$contrat->acheteur->nom.')'; } } else { $contrat->acheteur->nom; } ?></p>
	<br />
	<div class="ligne_form">
		<?php echo $form['volume']->renderLabel() ?>
		<?php echo $form['volume']->render() ?> HL
		<span class="error"><?php echo $form['volume']->renderError() ?></span>
	</div>
	<div class="ligne_form_btn">
		<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>