<?php use_helper('Rectificative'); ?>
<?php use_helper('Float'); ?>
<?php foreach($drm->declaration->certifications as $certification): ?>
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
				<?php foreach($certification->appellations as $appellation): ?>
				<tr class="alt">
					<td><?php echo $appellation->getConfig()->libelle ?></td>
					<td class="<?php echo isRectifierCssClass($appellation, 'total_debut_mois') ?>"><strong><?php echoFloat($appellation->total_debut_mois) ?></strong></td>
					<td class="<?php echo isRectifierCssClass($appellation, 'total_entrees') ?>"><?php echoFloat($appellation->total_entrees) ?></td>
					<td class="<?php echo isRectifierCssClass($appellation, 'total_sorties') ?>"><?php echoFloat($appellation->total_sorties) ?></td>
					<td class="<?php echo isRectifierCssClass($appellation, 'total') ?>"><strong><?php echoFloat($appellation->total) ?></strong></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endforeach; ?>