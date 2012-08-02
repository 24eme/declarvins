<?php 
	$value = $douane->value;
	$key = $douane->key;
?>
<tr>
	<td><?php echo $value[DouaneAllView::VALUE_DOUANE_NOM] ?></td>
	<td><?php echo $value[DouaneAllView::VALUE_DOUANE_IDENTIFIANT] ?></td>
	<td><?php echo $value[DouaneAllView::VALUE_DOUANE_EMAIL] ?></td>
	<td style="text-align:center;"><?php echo $value[DouaneAllView::VALUE_DOUANE_STATUT] ?></td>
	<td><a href="<?php echo url_for('douane_etablissements', array('id' => $key)) ?>">Etablissements</a> | <a href="<?php echo url_for('douane_modification', array('id' => $key)) ?>">Modifier</a> | <?php if ($value[DouaneAllView::VALUE_DOUANE_STATUT] == Douane::STATUT_ACTIF): ?><a href="<?php echo url_for('douane_desactiver', array('id' => $key)) ?>">Désactiver</a><?php else: ?><a href="<?php echo url_for('douane_activer', array('id' => $key)) ?>">Activer</a><?php endif; ?></td>
</tr>