<ul>
<?php foreach ($compte->interpro as $interpro => $value): ?>
	<li><strong><?php echo $interpro ?></strong> (<i><?php echo ($value->statut == _Compte::STATUT_VALIDATION_VALIDE)? 'validé' : 'en attente'; ?></i>)</li>
<?php endforeach; ?>
</ul>