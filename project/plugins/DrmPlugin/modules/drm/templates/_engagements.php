<h2>Engagements</h2>
<?php if($form->hasErrors()){ ?>
<div class="error_list">Veuillez cocher toutes les cases.</div>
<?php } ?>
<ol>
    <?php foreach ($drmValidation->getEngagements() as $engagement): ?>
        <li>
            <?php echo $form['engagement_' . $engagement->getCode()]->renderLabel() ?>
            <?php echo $form['engagement_' . $engagement->getCode()]->render() ?>
        </li>
    <?php endforeach; ?>
</ol>
<br />