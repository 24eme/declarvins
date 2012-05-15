<?php use_helper('Produit'); ?>
<?php use_helper('Rectificative'); ?>
<?php use_helper('Float'); ?>
<?php foreach($drm->produits as $certification_produit): ?>
	<div class="tableau_ajouts_liquidations">
		<h2><?php echo $certification_produit->getConfig()->libelle ?></h2>
		<table class="tableau_recap">
			<thead>
				<tr>
					<td style="border: none;">&nbsp;</td>
					<th style="font-weight: bold; border: none;">Stock début de mois</th>
					<th style="font-weight: bold; border: none;">Entrées</th>
					<th style="font-weight: bold; border: none;">Sorties</th>
					<th style="font-weight: bold; border: none;"><strong>Stock fin de mois</strong></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($certification_produit as $appellation_produit): 
                                        $i = 1;
                                ?>
					<?php foreach($appellation_produit as $produit): 
                                                $i++; ?>
						<?php $detail = $produit->getDetail(); ?>
						<tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
							<td><?php echo produitLibelleFromDetail($detail) ?></td>
                                                        <td class="<?php echo isRectifierCssClass($detail, 'total_debut_mois') ?>"><strong><?php echoFloat($detail->total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span></td>
							<td class="<?php echo isRectifierCssClass($detail, 'total_entrees') ?>"><?php echoFloat($detail->total_entrees) ?>&nbsp;<span class="unite">hl</span></td>
							<td class="<?php echo isRectifierCssClass($detail, 'total_sorties') ?>"><?php echoFloat($detail->total_sorties) ?>&nbsp;<span class="unite">hl</span></td>
							<td class="<?php echo isRectifierCssClass($detail, 'total') ?>"><strong><?php echoFloat($detail->total) ?></strong>&nbsp;<span class="unite">hl</span></td>
						</tr>
					<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endforeach; ?>