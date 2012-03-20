<?php use_helper('Float'); ?>
<?php if ($item->get($hash)): ?>
<?php echoFloatFr($item->get($hash)) ?>
<?php else: ?>
<span class="zero"><?php echoFloatFr($item->get($hash)) ?></span>
<?php endif; ?>

