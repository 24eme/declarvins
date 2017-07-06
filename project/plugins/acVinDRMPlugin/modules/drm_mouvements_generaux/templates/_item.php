<?php use_helper('Float'); ?>
<tr>
    <td>
    	<?php if (!$detail->total_debut_mois && !$detail->hasStockFinDeMoisDRMPrecedente()): ?>
    	<a href="<?php echo url_for('drm_mouvements_generaux_produit_delete', $detail) ?>" class="supprimer">Supprimer</a>
    	<?php endif; ?>
    	<?php echo $detail->getFormattedLibelle(ESC_RAW); ?>
    	<?php
    	$drm = $detail->getDocument();
		if ($drm->getEtablissement()->isTransmissionCiel()): 
		?>
    	<a href="<?php echo url_for('drm_mouvements_generaux_product_edit', $detail) ?>" class="btn_popup" data-popup="#popup_edit_produit_<?php echo $detail->getIdentifiantHTML() ?>" data-popup-config="configFormEdit"><img src="/images/pictos/pi_edit.png" alt="edit" /></a>
    	<?php endif; ?>
    </td>
	<td>
        <?php echo echoLongFloat($detail->total_debut_mois) ?> <span class="unite">hl </span>
	</td>
	<td class="acqTd <?php if ($detail->getDocument()->droits_acquittes): ?>showTd<?php else: ?>noTd<?php endif; ?>">
        <?php echo echoLongFloat($detail->acq_total_debut_mois) ?> <span class="unite">hl </span>
	</td>
	<td>
		<?php echo $form['pas_de_mouvement_check']->render(array("class" => "pas_de_mouvement")) ?>
	</td>
</tr>