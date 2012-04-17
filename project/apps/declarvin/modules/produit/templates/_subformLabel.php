<div class="ligne_form">
	<table>
		<tr>
			<td><span class="error"><?php echo $form['label']->renderError() ?></span><?php echo $form['label']->renderLabel() ?><br /><?php echo $form['label']->render() ?></td>
			<td><span class="error"><?php echo $form['code']->renderError() ?></span><?php echo $form['code']->renderLabel() ?><br /><?php echo $form['code']->render() ?></td>
		</tr>
	</table>
</div>