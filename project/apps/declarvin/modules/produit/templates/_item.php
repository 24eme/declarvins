<?php 
	$values = $produit->value; 
	$departements = $values->departements->getRawValue();
	$labels = $values->labels->getRawValue();
	$douane = $values->douane->getRawValue();
	$cvo = $values->cvo->getRawValue();
?>
<tr>
	<td><?php if ($produit->key[1]): ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[1] ?></a><?php endif; ?></td>
	<td><?php if ($produit->key[2]): ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'appellation', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[2] ?></a><?php endif; ?></td>
	<td><?php if ($produit->key[3]): ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'lieu', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[3] ?></a><?php endif; ?></td>
	<td><?php if ($produit->key[4]): ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'couleur', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[4] ?></a><?php endif; ?></td>
	<td><?php if ($produit->key[5]): ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'cepage', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[5] ?></a><?php endif; ?></td>
	<td><?php if ($produit->key[6]): ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'millesime', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[6] ?></a><?php endif; ?></td>
	<td><?php echo implode(', ', $departements) ?></td>
	<td><?php echo implode(', ', $labels) ?></td>
	<td><?php echo ($douane->taux)? $douane->taux.'% ('.$douane->code.')' : null; ?></td>
	<td><?php echo ($cvo->taux)? $cvo->taux.'%' : null; ?></td>		
</tr>