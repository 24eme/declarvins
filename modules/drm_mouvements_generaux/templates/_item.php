<tr>
    <td>
    	<?php if (!$detail->total_debut_mois && !$detail->hasStockFinDeMoisDRMPrecedente()): ?>
    	<a href="<?php echo url_for('drm_mouvements_generaux_produit_delete', $detail) ?>" class="supprimer">Supprimer</a>
    	<?php endif; ?>
    	<?php echo $detail->getFormattedLibelle(ESC_RAW); ?>
    </td>
	<td>
        <?php echo $detail->total_debut_mois ?> <span class="unite">hl </span>
	</td>
	<td>
		<?php echo $form['pas_de_mouvement_check']->render(array("class" => "pas_de_mouvement")) ?>
	</td>
</tr>