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
    $item = $hit->getData();
    ?>
    	<tr>
    	
    		<?php foreach ($statistiquesConfig['champs'] as $champs): ?>
    		<?php 
    			$value = null;
    			$noeud = $champs['noeud'];
    			if ($champs['need_replace']) {
    				$noeud = str_replace($champs['replace'], ${$champs['var_replace']}, $noeud);
    			}
    			foreach (explode('.', $noeud) as $v) {$value = ($value)? $value[$v] : $item[$v]; }
    		?>
    		<?php if (is_object($value)) {$value = $value->getRawValue();} ?>
    		<?php if (is_array($value)): ?>
    		<td><?php echo implode(', ', $value); ?></td>
    		<?php else: ?>
    		<td><?php echo ($champs['print_number'])? number_format($value, 2, ',', ' ') : $value; ?></td>
    		<?php endif; ?>
    		<?php endforeach; ?>
    	</tr>
    <?php endforeach; ?>
    </tbody>
</table>
