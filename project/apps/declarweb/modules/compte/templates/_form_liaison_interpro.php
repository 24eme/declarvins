<form method="post" action="<?php echo url_for('@compte_liaison_interpro') ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['interpro']->renderError() ?>
        <?php echo $form['interpro']->render() ?>
    </div>

    <div class="btnValidation">
        <input type="image" src="/images/boutons/btn_valider.png" alt="Lier" />
    </div>
</form>