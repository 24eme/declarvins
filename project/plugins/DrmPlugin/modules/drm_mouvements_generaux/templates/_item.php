<?php use_helper('Produit'); ?>

<tr>
    <td>
    	<?php if (!$form->getObject()->getDetail()->total_debut_mois && !$form->getObject()->getDetail()->hasStockFinDeMoisDrmPrecedente()): ?>
    	<a href="<?php echo url_for('drm_mouvements_generaux_produit_delete', $form->getObject()) ?>" class="supprimer">Supprimer</a>
    	<?php endif; ?>
    	<?php echo produitLibelleFromDetail($form->getObject()->getDetail()); ?>
    </td>
	<td>
            <span class="unite"> <?php echo $form->getObject()->getDetail()->total_debut_mois ?> hl </span>
	</td>
	<td>
		<form class="updateProduct" action="<?php echo url_for('drm_mouvements_generaux_produit_update', $form->getObject()) ?>" method="post">
			<?php echo $form->renderHiddenFields() ?>
			<?php echo $form['pas_de_mouvement']->render(array("class" => "pas_de_mouvement")) ?>
		</form>
	</td>
</tr>