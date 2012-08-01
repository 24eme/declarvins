<?php 
	$values = $produit->value;
	$departements = (isset($values->departements))? $values->departements->getRawValue() : array();
	$labels = (isset($values->labels))? $values->labels->getRawValue() : array();
	$douane = (isset($values->douane))? $values->douane->getRawValue() : null;
	$cvo = (isset($values->cvo))? $values->cvo->getRawValue() : null;
	$entrees = (isset($values->entrees))? $values->entrees->getRawValue() : null;
	$sorties = (isset($values->sorties))? $values->sorties->getRawValue() : null;
?>
<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td><?php if ($supprimable): ?><a href="<?php echo url_for('produit_suppression', array('noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression du produit?')">Supprimer</a><?php endif; ?><a href="<?php echo url_for('produit_modification', array('noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[1])? $produit->key[1] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'genre', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[2])? $produit->key[2] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'appellation', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[3])? $produit->key[3] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'lieu', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[5])? $produit->key[5] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'couleur', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[6])? $produit->key[6] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'cepage', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[7])? $produit->key[7] : 'Défaut' ?></a></td>
	<td class="center"><?php echo implode(', ', $departements) ?></td>
	<td class="center"><?php echo implode(', ', $labels) ?></td>
	<td class="center"><?php if ($douane) {echo ($douane->taux)? $douane->taux.'<br />('.$douane->code.')' : null;} ?></td>
	<td class="center"><?php if ($cvo) {echo ($cvo->taux)? $cvo->taux.'<br />('.$cvo->code.')' : null;} ?></td>	
	<td class="center"><?php if ($entrees) {echo ($entrees->repli)? 'E' : null;} ?><?php if ($sorties) {echo ($sorties->repli)? 'S' : null;} ?></td>
	<td class="center"><?php if ($entrees) {echo ($entrees->declassement)? 'E' : null;} ?><?php if ($sorties) {echo ($sorties->declassement)? 'S' : null;} ?></td>		
</tr>