<div id="popup_ajout_detail" class="popup_contenu">
	<form  class="popup_form" id="form_ajout_detail" action="<?php echo url_for('drm_recap_ajout_ajax', $config_appellation) ?>" method="post">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<div class="ligne_form">
			<label>Appellation</label>
			<strong><?php echo $config_appellation->libelle ?></strong>
		</div>
		<div class="ligne_form">
			<?php echo $form['couleur']->renderLabel() ?>
			<?php echo $form['couleur']->render() ?>
			<span class="error"><?php echo $form['couleur']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['label']->renderLabel() ?>
			<div style="width: 240px; height: 100px; display: inline-block; overflow-x: hidden; overflow-y: scroll;">
				<?php echo $form['label']->render(array('class' => 'select_multiple')) ?>
			</div>
			<span class="error"><?php echo $form['label']->renderError() ?></span>
		</div>
		<div class="ligne_form">
			<?php echo $form['label_supplementaire']->renderLabel() ?>
			<?php echo $form['label_supplementaire']->render() ?>
			<span class="error"><?php echo $form['label_supplementaire']->renderError() ?></span>
		</div>
		<div class="ligne_form_btn">
			<button name="annuler" class="btn_annuler btn_fermer" type="reset">Annuler</button>
			<button name="valider" class="btn_valider" type="submit">Valider</button>
		</div>
	</form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form_ajout_detail').submit( function () {
            $.post($(this).attr('action'), $(this).serializeArray(), 
            function (data) {
                if(data.success) {
                    document.location.href = data.url;
                } else {
                    $('#popup_ajout_detail').html(data.content);
                }
            }, "json");
            return false;
        });
    })
</script>