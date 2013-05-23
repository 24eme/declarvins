<table>
<?php 
	foreach ($facets as $name => $stats):
?>
	<tr>
    	<th><?php echo $name ?> : </th>
    	<td><?php echo $stats['total'] ?>hl</td>
    </tr>
<?php endforeach; ?>
</table>