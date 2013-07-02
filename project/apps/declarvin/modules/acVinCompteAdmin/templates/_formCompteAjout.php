<form method="post" action="<?php echo url_for(array('sf_route' => 'compte_ajout')); ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form['login']->renderLabel() ?>
        <?php echo $form['login']->render() ?>
        <?php echo $form['login']->renderError() ?>
    </div>
    <?php 
      include_partial('acVinCompteAdmin/formCompteRenderer', array('form' => $form));  
    ?>
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
        <input class="btn_valider" type="submit" value="Ajouter"/>
    </div>
</form>