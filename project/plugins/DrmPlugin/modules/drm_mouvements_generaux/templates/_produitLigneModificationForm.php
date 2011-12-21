<tr>
	<td colspan="7">
		<form class="updateProduct" action="<?php echo url_for('drm_mouvements_generaux_produit_update', $form->getObject()) ?>" method="post">
			<?php echo $form->renderHiddenFields() ?>
			<table width="100%">
				<tr>
				    <td align="center" width="240">
				    <?php echo ConfigurationClient::getCurrent()->declaration
    														    ->labels->get($form->getObject()->getCertification()->getKey())
    															->appellations->get($form->getObject()->getAppellation()->getKey())
    															->libelle ?>
    				</td>
					<td align="center" width="100"><?php echo $form->getObject()->couleur ?></td>
					<td align="center" width="150"><?php echo implode(', ', $form->getObject()->label->toArray()) ?></td>
					<td align="center" width="100"><?php echo $form->getObject()->label_supplementaire ?></td>
					<td align="center" width="80"><?php echo $form->getObject()->disponible ?></td>
					<td align="center" width="80"><?php echo $form['stock_vide']->render() ?></td>
					<td align="center" width="80"><?php echo $form['pas_de_mouvement']->render() ?></td>
				</tr>
			</table>
		</form>
	</td>
</tr>