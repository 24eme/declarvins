<div style="text-align: right; margin: 20px 0;">
<?php if ($page > 1): ?>
<a class="pagination_link" href="<?php echo url_for('statistiques_'.$type, array('p' => ($page - 1))) ?>">&lt;&lt;</a>
<?php endif; ?>
(<strong><?php echo $page ?></strong>/<?php echo $nbPage ?>)
<?php if ($page < $nbPage): ?>
<a class="pagination_link" href="<?php echo url_for('statistiques_'.$type, array('p' => ($page + 1))) ?>">&gt;&gt;</a>
<?php endif; ?>
</div>