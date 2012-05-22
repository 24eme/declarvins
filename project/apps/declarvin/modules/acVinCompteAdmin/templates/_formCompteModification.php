<form method="post" action="<?php echo url_for(array('sf_route' => 'compte_modification', 'login' => $form->getObject()->login)); ?>">
    <?php 
        include_partial('acVinCompteAdmin/formCompteRenderer', array('form' => $form));
    ?>
        <div class="ligne_form ligne_form_label">
        <?php echo $form['login']->renderLabel() ?>
        <?php echo $form['login']->render() ?>
        <?php echo $form['login']->renderError() ?>
        </div>
        <br />
        <div class="ligne_form ligne_entiere ecart_check">
            <?php echo $form['droits']->renderLabel() ?>
            <?php echo $form['droits']->render() ?>
            <?php echo $form['droits']->renderError() ?>
        </div>
        <br />
        <div class="btnValidation">
            <input class="btn_valider" type="submit" value="Modifier"/>
        </div>
</form>