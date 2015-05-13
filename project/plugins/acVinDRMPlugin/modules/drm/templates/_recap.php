<?php use_helper('Version'); ?>
<?php use_helper('Float'); ?>
<?php 
	foreach($drm->declaration->certifications as $certification): 
	$details = $certification->getProduits();
	$i = 1;
	if (!(count($details) > 0)) {
		continue;
	}
?>
	<div class="tableau_ajouts_liquidations">
		<h2><?php echo $certification->getConfig()->libelle ?></h2>
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

				<?php foreach($details as $detail): 
                        $i++; ?>
						<tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
							<td>
								<?php echo $detail->getFormattedLibelle(ESC_RAW) ?>
				            </td>
                            <td class="<?php echo isVersionnerCssClass($detail, 'total_debut_mois') ?>"><strong><?php echoLongFloat($detail->total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span></td>
							<td class="<?php echo isVersionnerCssClass($detail, 'total_entrees') ?>"><?php echoLongFloat($detail->total_entrees) ?>&nbsp;<span class="unite">hl</span></td>
							<td class="<?php echo isVersionnerCssClass($detail, 'total_sorties') ?>"><?php echoLongFloat($detail->total_sorties) ?>&nbsp;<span class="unite">hl</span></td>
							<td class="<?php echo isVersionnerCssClass($detail, 'total') ?>"><strong><?php echoLongFloat($detail->total) ?></strong>&nbsp;<span class="unite">hl</span></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endforeach; ?>