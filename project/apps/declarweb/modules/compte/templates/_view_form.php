<div id="modification_compte">

    <div class="presentation clearfix"<?php if ($form->hasErrors()) echo ' style="display:none;"'; ?>>
        <?php if($sf_user->hasFlash('maj')) : ?>
            <p class="flash_message"><?php echo $sf_user->getFlash('maj'); ?></p>
        <?php endif; ?>
        <p><span>Nom :</span> <?php echo $compte->nom; ?></p>
        <p><span>Prénom :</span> <?php echo $compte->prenom; ?></p>
        <p><span>Téléphone :</span> <?php echo $compte->telephone; ?></p>
        <p><span>Fax :</span> <?php echo $compte->fax; ?></p>
        <p><span>Email :</span> <?php echo $compte->email; ?></p>
        <p><span>Mot de passe :</span> ****** </p>
        <div class="btn">
            <a href="javascript:void(0)" class="modifier"><img src="/images/boutons/btn_modifier_infos.png" alt="Modifier les informations" /></a>
        </div>
    </div>


    <div class="modification clearfix"<?php if (!$form->hasErrors()) echo ' style="display:none;"'; ?>>

        <form method="post" action="<?php echo url_for('@validation_compte') ?>">
            <div class="ligne_form ligne_form_label">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>

                <?php echo $form['nom']->renderError() ?>
                <?php echo $form['nom']->renderLabel() ?>
                <?php echo $form['nom']->render() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <?php echo $form['prenom']->renderError() ?>
                <?php echo $form['prenom']->renderLabel() ?>
                <?php echo $form['prenom']->render() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <?php echo $form['telephone']->renderError() ?>
                <?php echo $form['telephone']->renderLabel() ?>
                <?php echo $form['telephone']->render() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <?php echo $form['fax']->renderError() ?>
                <?php echo $form['fax']->renderLabel() ?>
                <?php echo $form['fax']->render() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <?php echo $form['email']->renderError() ?>
                <?php echo $form['email']->renderLabel() ?>
                <?php echo $form['email']->render() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <?php echo $form['mdp1']->renderError() ?>
                <?php echo $form['mdp1']->renderLabel() ?>
                <?php echo $form['mdp1']->render() ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['mdp2']->renderError() ?>
                <?php echo $form['mdp2']->renderLabel() ?>
                <?php echo $form['mdp2']->render() ?>
            </div>

            <div class="btn">
                <a href="javascript:void(0)" class="annuler"><img src="/images/boutons/btn_annuler.png" alt="Annuler" /></a>
                <input type="submit" value="Valider" />
            </div>
        </form>
    </div>


</div>