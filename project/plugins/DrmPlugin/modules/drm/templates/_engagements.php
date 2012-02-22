<h3>Engagements</h3>
<ol>
<?php foreach ($drmValidation->getEngagements() as $engagement): ?>
	<li>
		<?php echo $form['engagement_'.$engagement->getCode()]->renderLabel() ?>
		<?php echo $form['engagement_'.$engagement->getCode()]->render() ?>
		<span class="error"><?php echo $form['engagement_'.$engagement->getCode()]->renderError() ?></span>
	</li>
<?php endforeach; ?>
</ol>
<br />