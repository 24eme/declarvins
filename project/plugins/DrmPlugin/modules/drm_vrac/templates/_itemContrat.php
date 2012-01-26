<tr>
	<td></td>
	<td align="center">
		<span><?php echo $form->getObject()->getKey() ?></span>
	</td>
	<td align="center">
		<form action="<?php echo url_for('vrac_update_volume', $form->getObject()) ?>" method="post">
			<?php echo $form->renderGlobalErrors() ?>
			<?php echo $form->renderHiddenFields() ?>
			<div class="ligne_form">
				<?php echo $form['volume']->render() ?>
				<span class="error"><?php echo $form['volume']->renderError() ?></span>
			</div>
			<div class="ligne_form_btn">
				<button name="valider" class="btn_valider" type="submit">OK</button>
			</div>
		</form>
	</td>
</tr>