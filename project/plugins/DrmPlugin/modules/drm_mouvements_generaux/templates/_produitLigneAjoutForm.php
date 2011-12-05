<tr id="currentForm">
	<td colspan="8">
		<form class="truc" id="subForm" action="<?php echo url_for('@drm_mouvements_generaux_save_form') ?>" method="post" onsubmit="return truc(this)">
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form->renderHiddenFields() ?>
		<input type="hidden" value="<?php echo $label ?>" name="label" />
		<table>
			<tr>
				<td align="center"><?php echo $form['appellation']->render() ?><br /><span class="error"><?php echo $form['appellation']->renderError() ?></span></td>
				<td align="center"><?php echo $form['couleur']->render() ?><br /><span class="error"><?php echo $form['couleur']->renderError() ?></span></td>
				<td align="center"><?php echo $form['denomination']->render() ?><br /><span class="error"><?php echo $form['denomination']->renderError() ?></span></td>
				<td align="center"><?php echo $form['label']->render() ?><br /><span class="error"><?php echo $form['label']->renderError() ?></span></td>
				<td align="center"><?php echo $form['disponible']->render() ?><br /><span class="error"><?php echo $form['disponible']->renderError() ?></span></td>
				<td align="center"><?php echo $form['stock_vide']->render() ?><br /><span class="error"><?php echo $form['stock_vide']->renderError() ?></span></td>
				<td align="center"><?php echo $form['pas_de_mouvement']->render() ?><br /><span class="error"><?php echo $form['pas_de_mouvement']->renderError() ?></span></td>
				<td align="center"><input type="submit" value="V" />&nbsp;&nbsp;<a href="javascript:void(0)" class="deleteRow">X</a></td>
			</tr>
		</table>
		</form>
	</td>
</tr>