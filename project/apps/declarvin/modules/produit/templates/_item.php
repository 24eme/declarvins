<?php 
	$values = $produit->value; 
	$departements = $values->departements->getRawValue();
	$labels = $values->labels->getRawValue();
	$douane = $values->douane->getRawValue();
	$cvo = $values->cvo->getRawValue();
?>
<tr>
	<td><?php echo $produit->key[1] ?></td>
	<td><?php echo $produit->key[2] ?></td>
	<td><?php echo $produit->key[3] ?></td>
	<td><?php echo $produit->key[4] ?></td>
	<td><?php echo $produit->key[5] ?></td>
	<td><?php echo $produit->key[6] ?></td>
	<td><?php echo implode(', ', $departements) ?></td>
	<td><?php echo implode(', ', $labels) ?></td>
	<td><?php echo ($douane->taux)? $douane->taux.'% ('.$douane->code.')' : null; ?></td>
	<td><?php echo ($cvo->taux)? $cvo->taux.'%' : null; ?></td>		
</tr>