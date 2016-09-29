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
		<h2><strong><?php echo $certification->getConfig()->libelle ?></strong> en droits suspendus</h2>
		<table class="tableau_recap">
			<thead>
				<tr>
					<td style="border: none;">&nbsp;</td>
					<th style="font-weight: bold; border: none; width: 120px;">Stock début de mois</th>
					<th style="font-weight: bold; border: none; width: 85px;">Entrées</th>
					<th style="font-weight: bold; border: none; width: 85px;">Sorties</th>
					<th style="font-weight: bold; border: none; width: 120px;"><strong>Stock fin de mois</strong></th>
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
	<?php if ($drm->hasDroitsAcquittes()): ?>
	<div class="tableau_ajouts_liquidations">
	<h2><strong><?php echo $certification->getConfig()->libelle ?></strong> en droits acquittés</h2>
	<table class="tableau_recap">
		<thead>
			<tr>
				<td style="border: none;">&nbsp;</td>
				<th style="font-weight: bold; border: none; width: 120px;">Stock début de mois</th>
				<th style="font-weight: bold; border: none; width: 85px;">Entrées</th>
				<th style="font-weight: bold; border: none; width: 85px;">Sorties</th>
				<th style="font-weight: bold; border: none; width: 120px;"><strong>Stock fin de mois</strong></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $detail): 
                        $i++; ?>
					<tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
						<td>
							<?php echo $detail->getFormattedLibelle(ESC_RAW) ?>
				            </td>
                            <td class="<?php echo isVersionnerCssClass($detail, 'acq_total_debut_mois') ?>"><strong><?php echoLongFloat($detail->acq_total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span></td>
						<td class="<?php echo isVersionnerCssClass($detail, 'acq_total_entrees') ?>"><?php echoLongFloat($detail->acq_total_entrees) ?>&nbsp;<span class="unite">hl</span></td>
						<td class="<?php echo isVersionnerCssClass($detail, 'acq_total_sorties') ?>"><?php echoLongFloat($detail->acq_total_sorties) ?>&nbsp;<span class="unite">hl</span></td>
						<td class="<?php echo isVersionnerCssClass($detail, 'acq_total') ?>"><strong><?php echoLongFloat($detail->acq_total) ?></strong>&nbsp;<span class="unite">hl</span></td>
					</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<?php endif;?>
<?php endforeach; ?>
<?php if ($drm->exist('crds') && count($drm->crds) > 0): ?>
<div class="tableau_ajouts_liquidations">
		<h2><strong>Comptabilité capsules CRD</strong></h2>
		<table class="tableau_recap">
			<thead>
				<tr>
					<td style="border: none;">&nbsp;</td>
					<th style="font-weight: bold; border: none; width: 120px;">Stock début de mois</th>
					<th style="font-weight: bold; border: none; width: 85px;">Entrées</th>
					<th style="font-weight: bold; border: none; width: 85px;">Sorties</th>
					<th style="font-weight: bold; border: none; width: 120px;"><strong>Stock fin de mois</strong></th>
				</tr>
			</thead>
			<tbody>

				<?php $i = 1; foreach($drm->crds as $crd): 
                        $i++; ?>
						<tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
							<td>
								<?php echo $crd->libelle ?>
				            </td>
                            <td class="<?php echo isVersionnerCssClass($crd, 'total_debut_mois') ?>"><strong><?php echo $crd->total_debut_mois ?></strong></td>
							<td class="<?php echo isVersionnerCssClass($crd->entrees, 'achat') ?> <?php echo isVersionnerCssClass($crd->entrees, 'excedents') ?> <?php echo isVersionnerCssClass($crd->entrees, 'retours') ?>"><?php echo $crd->getTotalEntrees() ?></td>
							<td class="<?php echo isVersionnerCssClass($crd->sorties, 'utilisees') ?> <?php echo isVersionnerCssClass($crd->sorties, 'detruites') ?> <?php echo isVersionnerCssClass($crd->sorties, 'manquantes') ?>"><?php echo $crd->getTotalSorties() ?></td>
							<td class="<?php echo isVersionnerCssClass($crd, 'total_fin_mois') ?>"><strong><?php echo $crd->total_fin_mois ?></strong></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>