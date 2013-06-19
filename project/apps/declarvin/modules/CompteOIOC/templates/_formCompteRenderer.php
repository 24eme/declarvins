<div class="ligne_form">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>

    <?php echo $form['oioc']->renderLabel() ?>
    <?php echo $form['oioc']->render() ?>
    <?php echo $form['oioc']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['nom']->renderLabel() ?>
    <?php echo $form['nom']->render() ?>
    <?php echo $form['nom']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['prenom']->renderLabel() ?>
    <?php echo $form['prenom']->render() ?>
    <?php echo $form['prenom']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['telephone']->renderLabel() ?>
    <?php echo $form['telephone']->render() ?>
    <?php echo $form['telephone']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['fax']->renderLabel() ?>
    <?php echo $form['fax']->render() ?>
    <?php echo $form['fax']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['email']->renderLabel() ?>
    <?php echo $form['email']->render() ?>
    <?php echo $form['email']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['mdp1']->renderLabel() ?>
    <?php echo $form['mdp1']->render() ?>
    <?php echo $form['mdp1']->renderError() ?>
</div>

<div class="ligne_form">
    <?php echo $form['mdp2']->renderLabel() ?>
    <?php echo $form['mdp2']->render() ?>
    <?php echo $form['mdp2']->renderError() ?>
</div>