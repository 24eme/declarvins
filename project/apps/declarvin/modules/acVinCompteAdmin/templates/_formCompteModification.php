    <form method="post" action="<?php echo url_for(array('sf_route' => 'compte', 'login' => $form->getObject()->login)); ?>">
        <div class="ligne_form ligne_form_label">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>

            <?php echo $form['nom']->renderLabel() ?>
            <?php echo $form['nom']->render() ?>
            <?php echo $form['nom']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_form_label">
            <?php echo $form['prenom']->renderLabel() ?>
            <?php echo $form['prenom']->render() ?>
            <?php echo $form['prenom']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_form_label">
            <?php echo $form['telephone']->renderLabel() ?>
            <?php echo $form['telephone']->render() ?>
            <?php echo $form['telephone']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_form_label">
            <?php echo $form['fax']->renderLabel() ?>
            <?php echo $form['fax']->render() ?>
            <?php echo $form['fax']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_form_label">
            <?php echo $form['email']->renderLabel() ?>
            <?php echo $form['email']->render() ?>
            <?php echo $form['email']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_form_label">
            <?php echo $form['mdp1']->renderLabel() ?>
            <?php echo $form['mdp1']->render() ?>
            <?php echo $form['mdp1']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_form_label">
            <?php echo $form['mdp2']->renderLabel() ?>
            <?php echo $form['mdp2']->render() ?>
            <?php echo $form['mdp2']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_entiere ecart_check">
            <?php echo $form['droits']->renderLabel() ?>
            <?php echo $form['droits']->render() ?>
            <?php echo $form['droits']->renderError() ?>
        </div>
        <br />
        <div class="btnValidation">
            <input class="btn_valider" type="submit" value="Valider"/>
        </div>
</form>