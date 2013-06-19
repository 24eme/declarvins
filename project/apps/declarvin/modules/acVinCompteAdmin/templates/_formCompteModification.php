<form method="post" action="<?php echo url_for(array('sf_route' => 'compte_modification', 'login' => $form->getObject()->login)); ?>">
    
    <div class="ligne_form">
        <label>Login</label>
        <span><strong><?php echo $form->getObject()->login ?></strong></span>
    </div>
    <?php 
    include_partial('acVinCompteAdmin/formCompteRenderer', array('form' => $form));
    ?>

    <div class="ligne_form reinit_mdp">
        <a href="<?php echo url_for('admin_compte_password', array('login' => $form->getObject()->login)) ?>" class="btn_orange">Redéfinir mon mot de passe</a>
    </div>

    <div class="ligne_form ligne_entiere ecart_check">
        <?php echo $form['droits']->renderLabel() ?>
        <?php echo $form['droits']->render() ?>
        <?php echo $form['droits']->renderError() ?>
    </div>

    <div class="ligne_form ligne_entiere ecart_check">
        <?php echo $form['acces']->renderLabel() ?>
        <?php echo $form['acces']->render() ?>
        <?php echo $form['acces']->renderError() ?>
    </div>
    
    <div class="btnValidation">
        <input class="btn_valider" type="submit" value="Modifier"/>
    </div>
</form>