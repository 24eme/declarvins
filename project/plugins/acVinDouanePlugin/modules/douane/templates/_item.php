<?php 
	$value = $douane->value;
	$key = $douane->id;
?>
<tr <?php if($alt): ?>class="alt"<?php endif; ?>>
	<td><?php echo $value[DouaneAllView::VALUE_DOUANE_NOM] ?></td>
	<td><?php echo $value[DouaneAllView::VALUE_DOUANE_IDENTIFIANT] ?></td>
	<td><?php echo $value[DouaneAllView::VALUE_DOUANE_EMAIL] ?></td>
	<td style="text-align:center;"><?php echo $value[DouaneAllView::VALUE_DOUANE_STATUT] ?></td>
	<td class="actions">
		<a href="<?php echo url_for('douane_etablissements', array('id' => $key)) ?>" class="btn_etablissement">Etablissements</a>
		<a href="<?php echo url_for('douane_modification', array('id' => $key)) ?>" class="btn_modifier">Modifier</a>
		<?php if ($value[DouaneAllView::VALUE_DOUANE_STATUT] == Douane::STATUT_ACTIF): ?>
			<a href="<?php echo url_for('douane_desactiver', array('id' => $key)) ?>" class="btn_desactiver">Désactiver</a>
		<?php else: ?>
			<a href="<?php echo url_for('douane_activer', array('id' => $key)) ?>" class="btn_activer">Activer</a>
		<?php endif; ?>
	</td>
</tr>