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
    		<?php $value = null; foreach (explode('.', $champs['noeud']) as $v) {$value = ($value)? $value[$v] : $drm[$v]; } ?>
    		<td><?php echo $value ?></td>
    		<?php endforeach; ?>
    	</tr>
    <?php endforeach; ?>
    </tbody>
</table>
