<div id="popup_ajout_appelation" class="popup_contenu">
	<form id="form_appellation_ajout" method="post" action="<?php echo url_for('drm_recap_appellation_ajout_ajax', $label) ?>" class="popup_form">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<div class="ligne_form">
			<label for="produit_appellation"><?php echo $form['appellation']->renderLabel() ?> </label>
			<?php echo $form['appellation']->render() ?>
			<span class="error"><?php echo $form['appellation']->renderError() ?></span>
		</div>
		
		<div class="ligne_form_btn">

			<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
			<button name="valider" class="btn_valider" type="submit">Valider</button>
		</div>
	</form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form_appellation_ajout').submit( function () {
            $.post($(this).attr('action'), $(this).serializeArray(), 
            function (data) {
                if(data.success) {
                    document.location.href = data.url;
                } else {
                    $('#popup_ajout_appelation').html(data.content);
                }
            }, "json");
            return false;
        });
    })
</script>