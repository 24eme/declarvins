<?php use_helper('Float'); ?>
<?php if ($item->get($hash)): ?>
<?php echoFloatFr($item->get($hash)) ?> <span class="unite"><?php echo $unite ?></span>
<?php else: ?>
<span class="zero"><?php echoFloatFr(0) ?> <span class="unite"><?php echo $unite ?></span></span>
<?php endif; ?>


