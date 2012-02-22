<h3>Points de vigilance</h3>
<?php foreach ($drmValidation->getWarnings() as $warning): ?>
	<li><?php echo $warning->getRawValue(); ?></li>
<?php endforeach; ?>
<br />
