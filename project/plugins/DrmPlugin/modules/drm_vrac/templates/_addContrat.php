<?php use_helper('Produit'); ?>

<tr>
	<td><?php echo produitLibelle($detail->getRawValue()->getConfig()->getLibelles(), $detail->getRawValue()->getLabelLibelles()); ?></td>
	<td align="center">
		<?php if ($detail->hasContratVrac()): ?>
                    <div class="btn">
						<a href="<?php echo url_for('drm_vrac_ajout_contrat', $detail) ?>" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_contrat_<?php echo $detail->getIdentifiant() ?>" data-popup-config="configAjoutProduit"></a>
					</div>
		<?php endif; ?>
	</td>
	<td align="center"></td>
</tr>