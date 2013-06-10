<div style="text-align: right; margin: 20px 0;">
<?php if ($page > 1): ?>
<a href="<?php echo url_for('statistiques', array($queryName => $query, 'p' => ($page - 1), 'type' => $type)) ?>">&lt;&lt;</a>
<?php endif; ?>
(<strong><?php echo $page ?></strong>/<?php echo $nbPage ?>)
<?php if ($page < $nbPage): ?>
<a href="<?php echo url_for('statistiques', array($queryName => $query, 'p' => ($page + 1), 'type' => $type)) ?>">&gt;&gt;</a>
<?php endif; ?>
</div>