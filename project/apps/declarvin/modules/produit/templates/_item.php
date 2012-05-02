<?php 
	$values = $produit->value;
	$departements = (isset($values->departements))? $values->departements->getRawValue() : array();
	$labels = (isset($values->labels))? $values->labels->getRawValue() : array();
	$douane = (isset($values->douane))? $values->douane->getRawValue() : null;
	$cvo = (isset($values->cvo))? $values->cvo->getRawValue() : null;
	$entrees = (isset($values->entrees))? $values->entrees->getRawValue() : null;
	$sorties = (isset($values->sorties))? $values->sorties->getRawValue() : null;
?>
<tr>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[1] ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'appellation', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[2] ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'lieu', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[3] ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'couleur', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[4] ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'cepage', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[5] ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'millesime', 'hash' => str_replace('/', '-', $produit->key[7]))) ?>" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo $produit->key[6] ?></a></td>
	<td><?php echo implode(', ', $departements) ?></td>
	<td><?php echo implode(', ', $labels) ?></td>
	<td><?php if ($douane) {echo ($douane->taux)? $douane->taux.'<br />('.$douane->code.')' : null;} ?></td>
	<td><?php if ($cvo) {echo ($cvo->taux)? $cvo->taux : null;} ?></td>	
	<td><?php if ($entrees) {echo ($entrees->repli)? 'E' : null;} ?><?php if ($sorties) {echo ($sorties->repli)? 'S' : null;} ?></td>
	<td><?php if ($entrees) {echo ($entrees->declassement)? 'E' : null;} ?><?php if ($sorties) {echo ($sorties->declassement)? 'S' : null;} ?></td>		
</tr>