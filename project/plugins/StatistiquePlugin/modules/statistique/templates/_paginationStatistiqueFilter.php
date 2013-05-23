<ul>
<?php for ($i = 0; $i < $nbPage; $i++): ?>
	<li><a href="<?php echo url_for('statistiques', array($queryName => $query, 'p' => ($i + 1), 'type' => $type)) ?>"><?php if ($i + 1 == $page): ?><strong><?php endif; ?><?php echo ($i + 1) ?><?php if ($i + 1 == $page): ?></strong><?php endif; ?></a></li>
<?php endfor; ?>
</ul>