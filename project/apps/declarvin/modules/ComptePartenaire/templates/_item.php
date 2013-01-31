<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td>
		<?php if ($compte->key[ComptePartenaire::VIEW_KEY_STATUT] == _Compte::STATUT_ARCHIVE): ?>
		<a href="<?php echo url_for("compte_partenaire_creation", array('login' => $compte->key[ComptePartenaire::VIEW_KEY_LOGIN])) ?>" class="creation" style="left: 5px;" onclick="return confirm('Confirmez-vous l\'activation du partenaire "<?php echo $compte->key[ComptePartenaire::VIEW_KEY_LOGIN] ?>" ?')">Activer</a>
		<?php else: ?>
		<a href="<?php echo url_for("compte_partenaire_suppression", array('login' => $compte->key[ComptePartenaire::VIEW_KEY_LOGIN])) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression du partenaire "<?php echo $compte->key[ComptePartenaire::VIEW_KEY_LOGIN] ?>" ?')">Supprimer</a>
		<?php endif; ?>
		<?php echo $compte->key[ComptePartenaire::VIEW_KEY_STATUT] ?>
	</td>
	<td><?php echo $compte->key[ComptePartenaire::VIEW_KEY_LOGIN] ?></td>
	<td><?php echo $compte->key[ComptePartenaire::VIEW_KEY_NOM] ?></td>
	<td><?php echo $compte->key[ComptePartenaire::VIEW_KEY_PRENOM] ?></td>
	<td><?php echo $compte->key[ComptePartenaire::VIEW_KEY_EMAIl] ?></td>
	<td><?php echo $compte->key[ComptePartenaire::VIEW_KEY_TELEPHONE] ?></td>		
	<td class="actions"><a class="btn_modifier"	href="<?php echo url_for("compte_partenaire_modification", array('login' => $compte->key[ComptePartenaire::VIEW_KEY_LOGIN])) ?>">Modifier</a></td>		
</tr>