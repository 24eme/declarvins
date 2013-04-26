<tr<?php if ($i%2): ?> class="alt"<?php endif;?>>
	<td>
		<?php echo $compte->key[CompteMandatsView::KEY_NUMERO_CONTRAT] ?> (<a href="<?php echo url_for("validation_fiche", array('num_contrat' => $compte->key[CompteMandatsView::KEY_NUMERO_CONTRAT])) ?>">fiche contrat</a>)
	</td>
	<td><?php echo $compte->key[CompteMandatsView::KEY_RAISON_SOCIALE] ?></td>
	<td><?php echo $compte->key[CompteMandatsView::KEY_LOGIN] ?></td>
	<td><?php echo $compte->key[CompteMandatsView::KEY_NOM] ?></td>
	<td><?php echo $compte->key[CompteMandatsView::KEY_PRENOM] ?></td>
	<td><?php echo $compte->key[CompteMandatsView::KEY_EMAIL] ?></td>
</tr>