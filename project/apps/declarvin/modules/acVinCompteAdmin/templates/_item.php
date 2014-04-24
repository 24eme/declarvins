<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td>
		<?php if ($compte->key[CompteAllView::KEY_STATUT] == _Compte::STATUT_ARCHIVE): ?>
		<a href="<?php echo url_for("compte_creation", array('login' => $compte->value[CompteAllView::VALUE_LOGIN])) ?>" class="creation" style="left: 5px;" onclick="return confirm('Confirmez-vous l'activation de l\'opérateur <?php echo $compte->value[CompteAllView::VALUE_LOGIN] ?> ?')">Activer</a>
		<?php else: ?>
		<a href="<?php echo url_for("compte_suppression", array('login' => $compte->value[CompteAllView::VALUE_LOGIN])) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression de l\'opérateur <?php echo $compte->value[CompteAllView::VALUE_LOGIN] ?> ?')">Supprimer</a>
		<?php endif; ?>
		<?php echo $compte->key[CompteAllView::KEY_STATUT] ?>
	</td>
	<td><?php echo $compte->value[CompteAllView::VALUE_LOGIN] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_NOM] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_PRENOM] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_EMAIL] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_TELEPHONE] ?></td>		
	<td class="actions"><a class="btn_modifier"	href="<?php echo url_for("compte_modification", array('login' => $compte->value[CompteAllView::VALUE_LOGIN])) ?>">Modifier</a></td>		
</tr>