<div class="ligne_form">
	<table>
		<tr>
			<td><span class="error"><?php echo $form['date']->renderError() ?></span><?php echo $form['date']->renderLabel() ?><br /><?php echo $form['date']->render() ?></td>
			<td><span class="error"><?php echo $form['code']->renderError() ?></span><?php echo $form['code']->renderLabel() ?><br /><?php echo $form['code']->render() ?></td>
			<td><span class="error"><?php echo $form['taux']->renderError() ?></span><?php echo $form['taux']->renderLabel() ?><br /><?php echo $form['taux']->render() ?></td>
		</tr>
	</table>
</div>