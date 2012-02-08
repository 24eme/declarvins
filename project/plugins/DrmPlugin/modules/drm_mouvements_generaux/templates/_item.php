<tr>
    <td>
    	<a href="<?php echo url_for('drm_mouvements_generaux_produit_delete', $form->getObject()) ?>" class="supprimer">Supprimer</a>
    	<?php echo implode(' ', array_merge($form->getObject()->getDetail()->getConfig()->getLibelles(),
    										array($form->getObject()->getDetail()->getLabelLibelle())
    									   )) ?>
    </td>
	<td>
		<?php echo $form->getObject()->getDetail()->total_debut_mois ?> HL
		<?php if ($form->getObject()->getDetail()->total_debut_mois != $form->getObject()->getDetail()->total): ?>
			=> <?php echo $form->getObject()->getDetail()->total ?> HL
		<?php endif; ?>
	</td>
	<td>
		<form class="updateProduct" action="<?php echo url_for('drm_mouvements_generaux_produit_update', $form->getObject()) ?>" method="post">
			<?php echo $form->renderHiddenFields() ?>
			<?php echo $form['pas_de_mouvement']->render() ?>
		</form>
	</td>
</tr>