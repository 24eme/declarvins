<form method="post" action="<?php echo url_for('@validation_liaison') ?>">
    <div id="formLiaisonInterpro" class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['interpro']->renderError() ?>
        <?php echo $form['interpro']->render() ?>
    </div>

    <div class="btnValidation">
        <input type="submit" value="Lier/Délier" />
    </div>
</form> 