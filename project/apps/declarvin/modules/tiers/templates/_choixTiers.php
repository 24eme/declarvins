<?php if ($hasMultiTiers): ?>
<form action="<?php echo url_for('@tiers') ?>" method="post">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>
	<?php echo $form['tiers']->render(array('onchange' => 'submit()')) ?>
</form>
<?php endif; ?>