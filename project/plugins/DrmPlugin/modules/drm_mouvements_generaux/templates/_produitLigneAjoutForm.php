<form id="subForm" action="<?php echo url_for('@drm_mouvements_generaux_product_form') ?>" method="post">
	<?php echo $form->renderGlobalErrors() ?>
	<?php echo $form->renderHiddenFields() ?>
	<input type="hidden" name="certification" value="<?php echo $certification ?>" />
	<table>
		<tr><td><?php echo $form['appellation']->renderLabel() ?></td><td><?php echo $form['appellation']->render() ?></td><td><span class="error"><?php echo $form['appellation']->renderError() ?></span></td></tr>
		<tr><td><?php echo $form['couleur']->renderLabel() ?></td><td><?php echo $form['couleur']->render() ?></td><td><span class="error"><?php echo $form['couleur']->renderError() ?></span></td></tr>
		<tr><td><?php echo $form['denomination']->renderLabel() ?></td><td><?php echo $form['denomination']->render() ?></td><td><span class="error"><?php echo $form['denomination']->renderError() ?></span></td></tr>
		<tr><td><?php echo $form['label']->renderLabel() ?></td><td><?php echo $form['label']->render() ?></td><td><span class="error"><?php echo $form['label']->renderError() ?></span></td></tr>
		<tr><td><?php echo $form['disponible']->renderLabel() ?></td><td><?php echo $form['disponible']->render() ?></td><td><span class="error"><?php echo $form['disponible']->renderError() ?></span></td></tr>
		<tr><td><?php echo $form['stock_vide']->renderLabel() ?></td><td><?php echo $form['stock_vide']->render() ?></td><td><span class="error"><?php echo $form['stock_vide']->renderError() ?></span></td></tr>
		<tr><td><?php echo $form['pas_de_mouvement']->renderLabel() ?></td><td><?php echo $form['pas_de_mouvement']->render() ?></td><td><span class="error"><?php echo $form['pas_de_mouvement']->renderError() ?></span></td></tr>
		<tr><td></td><td><input type="submit" value="Ajouter" />&nbsp;&nbsp;<a href="javascript:void(0)" class="closeForm">Annuler</a></td><td></td></tr>
	</table>
</form>