<form action="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>" method="post" enctype="multipart/form-data">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>

    <?php echo $form['file']->render() ?>
    <?php echo $form['file']->renderError() ?>

        <div class="btn">
                <button class="btn_valider" type="submit">Envoyer</button>
            </div>
</form>