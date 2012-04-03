<form method="post" action="<?php echo url_for('@validation_login') ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['interpro']->renderLabel() ?>
        <?php echo $form['interpro']->render() ?>
        <?php echo $form['interpro']->renderError() ?>
    </div>
    <br />
    <div class="ligne_form ligne_form_label">
        <?php echo $form['contrat']->renderLabel() ?>
        <?php echo $form['contrat']->render() ?>
        <?php echo $form['contrat']->renderError() ?>
    </div>
	<br />
    <div class="btnValidation">
    	<span>&nbsp;</span>
        <input class="btn_valider" type="submit" value="Valider" />
    </div>
</form>