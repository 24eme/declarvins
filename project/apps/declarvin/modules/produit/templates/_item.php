<?php 
	$values = $produit->value;
	$departements = (isset($values->departements))? $values->departements->getRawValue() : array();
	$labels = (isset($values->labels))? $values->labels->getRawValue() : array();
	$douane = (isset($values->douane))? $values->douane->getRawValue() : null;
	$cvo = (isset($values->cvo))? $values->cvo->getRawValue() : null;
	$entrees = (isset($values->entrees))? $values->entrees->getRawValue() : null;
	$sorties = (isset($values->sorties))? $values->sorties->getRawValue() : null;
	$oioc = (isset($values->oioc))? str_replace(OIOC::OIOC_KEY, '', $values->oioc) : null;
	$hasVrac = (isset($values->has_vrac) && $values->has_vrac)? 'oui' : 'non';
	
	$noeudDepartements = "Valeurs définies dans le noeud ".strtolower($values->noeud_departements);
	$noeudLabels = "Valeurs définies dans le noeud ".strtolower($values->noeud_labels);
	$noeudDouane = "Valeurs définies dans le noeud ".strtolower($values->noeud_douane);
	$noeudCvo = "Valeurs définies dans le noeud ".strtolower($values->noeud_cvo);
	$noeudRepli = "Valeurs définies dans le noeud ".strtolower($values->noeud_repli);
	$noeudDeclassement = "Valeurs définies dans le noeud ".strtolower($values->noeud_declassement);
	$noeudOIOC = "Valeurs définies dans le noeud ".strtolower($values->noeud_oioc);
	$noeudVrac = "Valeurs définies dans le noeud ".strtolower($values->noeud_has_vrac);
	
	$categorie = "Noeud définisant".(($values->noeud_departements == 'Catégorie')? ' departement' : null).(($values->noeud_labels == 'Catégorie')? ' labels' : null).(($values->noeud_douane == 'Catégorie')? ' douane' : null).(($values->noeud_cvo == 'Catégorie')? ' cvo' : null).(($values->noeud_repli == 'Catégorie')? ' repli' : null).(($values->noeud_declassement == 'Catégorie')? ' declassement' : null);
	$genre = "Noeud définisant".(($values->noeud_departements == 'Genre')? ' departement' : null).(($values->noeud_labels == 'Genre')? ' labels' : null).(($values->noeud_douane == 'Genre')? ' douane' : null).(($values->noeud_cvo == 'Genre')? ' cvo' : null).(($values->noeud_repli == 'Genre')? ' repli' : null).(($values->noeud_declassement == 'Genre')? ' declassement' : null);
	$denomination = "Noeud définisant".(($values->noeud_departements == 'Dénomination')? ' departement' : null).(($values->noeud_labels == 'Dénomination')? ' labels' : null).(($values->noeud_douane == 'Dénomination')? ' douane' : null).(($values->noeud_cvo == 'Dénomination')? ' cvo' : null).(($values->noeud_repli == 'Dénomination')? ' repli' : null).(($values->noeud_declassement == 'Dénomination')? ' declassement' : null);
	$lieu = "Noeud définisant".(($values->noeud_departements == 'Lieu')? ' departement' : null).(($values->noeud_labels == 'Lieu')? ' labels' : null).(($values->noeud_douane == 'Lieu')? ' douane' : null).(($values->noeud_cvo == 'Lieu')? ' cvo' : null).(($values->noeud_repli == 'Lieu')? ' repli' : null).(($values->noeud_declassement == 'Lieu')? ' declassement' : null);
	
	if ($categorie == "Noeud définisant")
		$categorie = "";
	if ($genre == "Noeud définisant")
		$genre = "";
	if ($denomination == "Noeud définisant")
		$denomination = "";
	if ($lieu == "Noeud définisant")
		$lieu = "";
?>
<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td><?php if ($supprimable): ?><a href="<?php echo url_for('produit_suppression', array('noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression du produit?')">Supprimer</a><?php endif; ?><a title="<?php echo $categorie ?>" href="<?php echo url_for('produit_modification', array('noeud' => 'certification', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[1])? $produit->key[1] : 'Défaut' ?></a></td>
	<td><a title="<?php echo $genre ?>" href="<?php echo url_for('produit_modification', array('noeud' => 'genre', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[2])? $produit->key[2] : 'Défaut' ?></a></td>
	<td><a title="<?php echo $denomination ?>" href="<?php echo url_for('produit_modification', array('noeud' => 'appellation', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[3])? $produit->key[3] : 'Défaut' ?></a></td>
	<td><a title="<?php echo $lieu ?>" href="<?php echo url_for('produit_modification', array('noeud' => 'lieu', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[5])? $produit->key[5] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'couleur', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[6])? $produit->key[6] : 'Défaut' ?></a></td>
	<td><a href="<?php echo url_for('produit_modification', array('noeud' => 'cepage', 'hash' => str_replace('/', '-', $produit->key[8]))) ?>" class="btn_edit btn_popup1" data-popup="popup_produit" data-popup-config="configForm"><?php echo ($produit->key[7])? $produit->key[7] : 'Défaut' ?></a></td>
	<td class="center" title="<?php echo $noeudOIOC ?>"><?php echo $oioc ?></td>
	<td class="center" title="<?php echo $noeudDepartements ?>"><?php echo implode(', ', $departements) ?></td>
	<td class="center" title="<?php echo $noeudLabels ?>"><?php echo implode(', ', $labels) ?></td>
	<td class="center" title="<?php echo $noeudDouane ?>"><?php if ($douane) {echo ($douane->taux)? $douane->taux.'<br />('.$douane->code.')' : null;} ?></td>
	<td class="center" title="<?php echo $noeudCvo ?>"><?php if ($cvo) {echo ($cvo->taux)? $cvo->taux.'<br />('.$cvo->code.')' : null;} ?></td>
	<td class="center" title="<?php echo $noeudVrac ?>"><?php echo $hasVrac ?></td>	
	<td class="center" title="<?php echo $noeudRepli ?>"><?php if ($entrees) {echo ($entrees->repli)? 'E' : null;} ?><?php if ($sorties) {echo ($sorties->repli)? 'S' : null;} ?></td>
	<td class="center" title="<?php echo $noeudDeclassement ?>"><?php if ($entrees) {echo ($entrees->declassement)? 'E' : null;} ?><?php if ($sorties) {echo ($sorties->declassement)? 'S' : null;} ?></td>		
</tr>