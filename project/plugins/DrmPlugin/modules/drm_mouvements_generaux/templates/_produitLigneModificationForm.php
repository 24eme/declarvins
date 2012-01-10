<tr>
    <td>
    	<a href="<?php echo url_for('drm_mouvements_generaux_produit_delete', $form->getObject()) ?>" class="supprimer">Supprimer</a>
    	<?php echo ConfigurationClient::getCurrent()->declaration
    														    ->certifications->get($form->getObject()->getCertification()->getKey())
    															->appellations->get($form->getObject()->getAppellation()->getKey())
    															->libelle ?>
    </td>
	<td><?php echo $form->getObject()->couleur ?></td>
	<td><?php echo implode(', ', $form->getObject()->label->toArray()) ?></td>
	<td><?php echo $form->getObject()->disponible ?>HL</td>
	<td>
		<form class="updateProduct" action="<?php echo url_for('drm_mouvements_generaux_produit_update', $form->getObject()) ?>" method="post">
			<?php echo $form->renderHiddenFields() ?>
			<?php echo $form['stock_vide']->render() ?>
			<?php echo $form['pas_de_mouvement']->render(array('style' => 'display: none;')) ?>
		</form>
	</td>
	<td>
		<form class="updateProduct" action="<?php echo url_for('drm_mouvements_generaux_produit_update', $form->getObject()) ?>" method="post">
			<?php echo $form->renderHiddenFields() ?>
			<?php echo $form['stock_vide']->render(array('style' => 'display: none;')) ?>
			<?php echo $form['pas_de_mouvement']->render() ?>
		</form>
	</td>
</tr>