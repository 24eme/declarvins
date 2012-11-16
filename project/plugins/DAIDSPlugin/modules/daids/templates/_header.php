<h1>DAI/DS</h1>
<p id="date_drm">
	DAI/DS <?php echo $daids->periode ?>
	<?php if($daids->isRectificative()): ?>
		- <strong style="color: #ff0000; text-transform: uppercase;">
			Rectificative n° <?php echo sprintf('%02d', $daids->rectificative) ?>
		</strong>
        <?php endif; ?>
<small>(saisie <?php echo $daids->getModeDeSaisieLibelle(); ?>)</small>
</p>
