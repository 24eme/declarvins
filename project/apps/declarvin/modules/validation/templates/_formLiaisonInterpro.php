<form id="formLiaisonInterpro" method="post" action="<?php echo url_for('@validation_liaison') ?>">
    <div id="liaisonInterpro" class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['interpro']->renderError() ?>
        <?php echo $form['interpro']->render() ?>
    </div>
    <div class="btn">
		<span>&nbsp;</span>
		<button class="btn_valider" type="submit">Lier/Délier</button>
	</div>
</form> 