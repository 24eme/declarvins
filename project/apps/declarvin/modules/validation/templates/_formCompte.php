<div id="modification_compte">

    <div class="presentation clearfix"<?php if ($form->hasErrors()) echo ' style="display:none;"'; ?>>
        <p><span>Nom:</span> <?php echo $compte->nom; ?></p>
        <p><span>Prénom:</span> <?php echo $compte->prenom; ?></p>
        <p><span>Téléphone:</span> <?php echo $compte->telephone; ?></p>
        <p><span>Fax:</span> <?php echo $compte->fax; ?></p>
        <p><span>Adresse e-mail:</span> <?php echo $compte->email; ?></p>
        <p><span>Mot de passe:</span> ****** </p>
        <div class="ligne_btn btn">
            <a href="javascript:void(0)" class="modifier btn_valider">Modifier</a>
        </div>
            <br />
        <a href="<?php echo url_for('admin_compte_password', array('login' => $compte->login)) ?>" class="">Lancer une procédure de redéfinition du mot de passe</a></p>
    </div>

        
    <div class="modification clearfix"<?php if (!$form->hasErrors()) echo ' style="display:none;"'; ?>>

        <form method="post" action="<?php echo url_for('validation_compte', array('num_contrat' => $contrat->no_contrat)) ?>">
            <div class="ligne_form ligne_form_label">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>

                <label><?php echo $form['nom']->renderLabel() ?></label>
                <?php echo $form['nom']->render() ?>
                <?php echo $form['nom']->renderError() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <label><?php echo $form['prenom']->renderLabel() ?></label>
                <?php echo $form['prenom']->render() ?>
                <?php echo $form['prenom']->renderError() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <label><?php echo $form['telephone']->renderLabel() ?></label>
                <?php echo $form['telephone']->render() ?>
                <?php echo $form['telephone']->renderError() ?>
            </div>
            <div class="ligne_form">
                <label><?php echo $form['fax']->renderLabel() ?></label>
                <?php echo $form['fax']->render() ?>
                <?php echo $form['fax']->renderError() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <label><?php echo $form['email']->renderLabel() ?></label>
                <?php echo $form['email']->render() ?>
                <?php echo $form['email']->renderError() ?>
            </div>
            <div class="ligne_form ligne_form_label">
                <label><?php echo $form['mdp1']->renderLabel() ?></label>
                <?php echo $form['mdp1']->render() ?>
                <?php echo $form['mdp1']->renderError() ?>
            </div>
            <div class="ligne_form">
                <label><?php echo $form['mdp2']->renderLabel() ?></label>
                <?php echo $form['mdp2']->render() ?>
                <?php echo $form['mdp2']->renderError() ?>
            </div>

            <div class="ligne_btn btn">
            	<button class="btn_valider" type="submit"><span>Valider</span></button>
                <a href="javascript:void(0)" class="btn_annuler">Annuler</a>
            </div>
        </form>
        
    </div>
    <br /><br />

</div>