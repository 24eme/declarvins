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
	foreach ($facets as $name => $stats):
?>
	<tr>
    	<th><?php echo $name ?> : </th>
    	<td><?php echo number_format($stats['total'], 2, ',', ' '); ?> <strong>hl</strong></td>
    </tr>
<?php endforeach; ?>
</table>