<table>
	<thead>
    	<tr>
    		<?php foreach ($statistiquesConfig['champs'] as $champs): ?>
    		<th><?php echo $champs['libelle'] ?></th>
    		<?php endforeach; ?>
    	</tr>
    </thead>
    <tbody>
    <?php 
    foreach ($hits as $hit): 
    $drm = $hit->getData();
    ?>
    	<tr>
    	
    		<?php foreach ($statistiquesConfig['champs'] as $champs): ?>
    		<?php 
    			$value = null;
    			$noeud = $champs['noeud'];
    			if ($champs['need_replace']) {
    				$noeud = str_replace($champs['replace'], ${$champs['var_replace']}, $noeud);
    			}
    			foreach (explode('.', $noeud) as $v) {$value = ($value)? $value[$v] : $drm[$v]; }
    		?>
    		<td><?php echo ($champs['print_number'])? number_format($value, 2, ',', ' ') : $value; ?></td>
    		<?php endforeach; ?>
    	</tr>
    <?php endforeach; ?>
    </tbody>
</table>
