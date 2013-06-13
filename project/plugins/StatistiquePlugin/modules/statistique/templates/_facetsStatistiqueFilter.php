<table>
	<tr>
    	<th>Nombre de document : </th>
    	<td><?php echo $nbDoc ?></td>
    </tr>
	<tr>
    	<th>&nbsp;</th>
    	<td>&nbsp;</td>
    </tr>
<?php 
	foreach ($configFacets as $configFacet):
		$stats = $facets[$configFacet['nom']];
		$divise = 1;
		if ($n = $configFacet['divise']) {
			$divise = $facets[$n]['total'];
		}
?>
	<tr>
    	<th><?php echo $configFacet['nom'] ?> : </th>
    	<td><?php echo number_format(($stats['total'] / $divise), 2, ',', ' '); ?> <strong><?php echo $configFacet['unite'] ?></strong>
    	</td>
    </tr>
<?php endforeach; ?>
</table>