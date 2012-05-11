<form id="form_appellation_ajout" method="post" action="<?php echo url_for('drm_recap_appellation_ajout_ajax', $certification) ?>" class="popup_form" data-popup-success="popup" data-popup="#popup_ajout_detail" data-popup-config="configForm" data-popup-titre="Ajouter un produit">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<div class="ligne_form">
		<span class="error"><?php echo $form['appellation']->renderError() ?></span>
		<?php echo $form['appellation']->renderLabel() ?> 
		<?php echo $form['appellation']->render() ?>
	</div>
	<div class="ligne_form_btn">
		<a href="#" onClick="location.reload(true); return false;" name="annuler" class="btn_annuler">Annuler</a>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function () {
	$( "#<?php echo $form['appellation']->renderId() ?>" ).combobox();
});
</script>