 <form method="post" action="<?php echo url_for('compte_new_chai', array('id' => $form->getObject()->getIdentifiant())) ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>
        <?php if (isset($form['identifiant'])): ?>
            <?php echo $form['identifiant']->renderError() ?>
            <?php echo $form['identifiant']->renderLabel() ?>
            <?php echo $form['identifiant']->render() ?>
        <?php endif; ?>
    </div>
    <div class="ligne_form ligne_form_label">
        <?php echo $form['nom']->renderError() ?>
        <?php echo $form['nom']->renderLabel() ?>
        <?php echo $form['nom']->render() ?>
    </div>
    <div class="ligne_form ligne_form_label">
        <?php echo $form['siret']->renderError() ?>
        <?php echo $form['siret']->renderLabel() ?>
        <?php echo $form['siret']->render() ?>
    </div>

    <div class="btn">
        <a href="<?php echo url_for('@compte') ?>">Annuler</a>
        <input type="image" src="/images/boutons/btn_valider.png" alt="Valider" />
    </div>
</form>