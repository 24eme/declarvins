<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td>
		<a href="<?php echo url_for("validation_suppression", array('num_contrat' => $compte->value[CompteAllView::VALUE_NUMERO_CONTRAT])) ?>" class="supprimer" style="left: 5px;" onclick="return confirm('Confirmez-vous la suppression du contrat d\'inscription n°<?php echo $compte->value[CompteAllView::VALUE_NUMERO_CONTRAT] ?> ?')">Supprimer</a>
		<?php echo $compte->value[CompteAllView::VALUE_NUMERO_CONTRAT] ?> (<a href="<?php echo url_for("validation_fiche", array('num_contrat' => $compte->value[CompteAllView::VALUE_NUMERO_CONTRAT])) ?>">fiche contrat</a>)
	</td>
	<td><?php echo $compte->value[CompteAllView::VALUE_RAISON_SOCIALE] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_LOGIN] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_NOM] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_PRENOM] ?></td>
	<td><?php echo $compte->value[CompteAllView::VALUE_EMAIL] ?></td>
	<td><?php echo ($compte->value[CompteAllView::VALUE_CIEL])? 'oui' : '' ?></td>
</tr>