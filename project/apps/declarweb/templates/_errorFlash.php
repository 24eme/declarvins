<?php if ($sf_user->hasFlash('error')): ?>
  <p><?php echo $sf_user->getFlash('error') ?></p>
<?php endif; ?>