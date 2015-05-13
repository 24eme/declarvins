<?php use_helper('Float'); ?>
<?php if ($item->exist($hash) && $item->get($hash)): ?>
<?php echoLongFloatFr($item->get($hash)) ?> <span class="unite"><?php echo $unite ?></span>
<?php else: ?>
<span class="zero"></span>
<?php endif; ?>


