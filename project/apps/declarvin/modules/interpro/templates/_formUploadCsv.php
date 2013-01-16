<form action="<?php echo $url ?>" method="post" enctype="multipart/form-data">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>

    <?php echo $form['file']->render() ?>
    <?php echo $form['file']->renderError() ?>

	<div class="btn">
		<button class="btn_valider" type="submit">Mettre à jour</button>
	</div>
</form>