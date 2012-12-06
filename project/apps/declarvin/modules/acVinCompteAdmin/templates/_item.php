<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td>
		<?php if ($compte->key[CompteVirtuel::VIEW_KEY_STATUT] == _Compte::STATUT_INACTIF): ?>
		<a href="<?php echo url_for("compte_creation", array('login' => $compte->key[CompteVirtuel::VIEW_KEY_LOGIN])) ?>" class="creation" style="left: 5px;" onclick="return confirm('Confirmez-vous l\'activation de l\'opérateur "<?php echo $compte->key[CompteVirtuel::VIEW_KEY_LOGIN] ?>" ?')">Activer</a>
		<?php else: ?>
		<a href="<?php echo url_for("compte_suppression", array('login' => $compte->key[CompteVirtuel::VIEW_KEY_LOGIN])) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression de l\'opérateur "<?php echo $compte->key[CompteVirtuel::VIEW_KEY_LOGIN] ?>" ?')">Supprimer</a>
		<?php endif; ?>
		<?php echo $compte->key[CompteVirtuel::VIEW_KEY_STATUT] ?>
	</td>
	<td><?php echo $compte->key[CompteVirtuel::VIEW_KEY_LOGIN] ?></td>
	<td><?php echo $compte->key[CompteVirtuel::VIEW_KEY_NOM] ?></td>
	<td><?php echo $compte->key[CompteVirtuel::VIEW_KEY_PRENOM] ?></td>
	<td><?php echo $compte->key[CompteVirtuel::VIEW_KEY_EMAIl] ?></td>
	<td><?php echo $compte->key[CompteVirtuel::VIEW_KEY_TELEPHONE] ?></td>		
	<td class="actions"><a class="btn_modifier"	href="<?php echo url_for("compte_modification", array('login' => $compte->key[CompteVirtuel::VIEW_KEY_LOGIN])) ?>">Modifier</a></td>		
</tr>