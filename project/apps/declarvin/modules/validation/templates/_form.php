<form method="post" action="<?php echo url_for('@validation_login') ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['interpro']->renderError() ?>
        <?php echo $form['interpro']->renderLabel() ?>
        <?php echo $form['interpro']->render() ?>
    </div>
    
    <div class="ligne_form ligne_form_label">
        <?php echo $form['contrat']->renderError() ?>
        <?php echo $form['contrat']->renderLabel() ?>
        <?php echo $form['contrat']->render() ?>
    </div>

    <div class="btnValidation">
        <input type="submit" value="Valider" />
    </div>
</form>