<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td>
		<?php if ($compte->key[CompteOIOC::VIEW_KEY_STATUT] == _Compte::STATUT_ARCHIVE): ?>
		<a href="<?php echo url_for("compte_oioc_creation", array('login' => $compte->key[CompteOIOC::VIEW_KEY_LOGIN])) ?>" class="creation" style="left: 5px;" onclick="return confirm('Confirmez-vous l\'activation du partenaire "<?php echo $compte->key[CompteOIOC::VIEW_KEY_LOGIN] ?>" ?')">Activer</a>
		<?php else: ?>
		<a href="<?php echo url_for("compte_oioc_suppression", array('login' => $compte->key[CompteOIOC::VIEW_KEY_LOGIN])) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression du partenaire "<?php echo $compte->key[CompteOIOC::VIEW_KEY_LOGIN] ?>" ?')">Supprimer</a>
		<?php endif; ?>
		<?php echo $compte->key[CompteOIOC::VIEW_KEY_STATUT] ?>
	</td>
	<td><?php echo str_replace(OIOC::OIOC_KEY, '', $compte->key[CompteOIOC::VIEW_KEY_OIOC]) ?></td>
	<td><?php echo $compte->key[CompteOIOC::VIEW_KEY_LOGIN] ?></td>
	<td><?php echo $compte->key[CompteOIOC::VIEW_KEY_NOM] ?></td>
	<td><?php echo $compte->key[CompteOIOC::VIEW_KEY_PRENOM] ?></td>
	<td><?php echo $compte->key[CompteOIOC::VIEW_KEY_EMAIl] ?></td>
	<td><?php echo $compte->key[CompteOIOC::VIEW_KEY_TELEPHONE] ?></td>		
	<td class="actions"><a class="btn_modifier"	href="<?php echo url_for("compte_oioc_modification", array('login' => $compte->key[CompteOIOC::VIEW_KEY_LOGIN])) ?>">Modifier</a></td>		
</tr>