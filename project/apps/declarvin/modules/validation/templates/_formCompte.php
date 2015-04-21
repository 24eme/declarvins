<div id="modification_compte">

    <div class="presentation" <?php if ($form->hasErrors()) echo ' style="display:none;"'; ?>>

        <div class="bloc_form">
            <div class="ligne_form ligne_form_alt">
                <label>Statut:</label> <?php echo $compte->statut; ?>
            </div>
            <div class="ligne_form ">
                <label>Nom:</label> <?php echo $compte->nom; ?>
            </div>
            <div class="ligne_form ligne_form_alt">
                <label>Prénom:</label> <?php echo $compte->prenom; ?>
            </div>
            <div class="ligne_form ">
                <label>Téléphone:</label> <?php echo $compte->telephone; ?>
            </div>
            <div class="ligne_form  ligne_form_alt">
                <label>Fax:</label> <?php echo $compte->fax; ?>
            </div>
            <div class="ligne_form ">
                <label>Adresse e-mail:</label> <?php echo $compte->email; ?>
            </div>
            <?php if ($compte->login): ?>
            <div class="ligne_form ligne_form_alt">
                <label>Login:</label> <?php echo $compte->login; ?>
            </div>
            <div class="ligne_form">
               <label>Mot de passe:</label> ******
            </div>
            <?php endif; ?>
        </div>

        <div class="ligne_btn btn">
            <a href="#" class="btn_modifier btn_valider">Modifier</a>
            <?php if ($compte->login): ?>
            <a href="<?php echo url_for('validation_compte_password', array('login' => $compte->login)) ?>" class="btn_mdp">Lancer une procédure de redéfinition du mot de passe</a>
            <?php endif; ?>
            <?php if ($compte->statut == _Compte::STATUT_ATTENTE): ?>
            <a style="float: right;" href="<?php echo url_for('validation_compte_inscription', array('num_contrat' => $contrat->no_contrat)) ?>" class="btn_mdp">Renvoyer le mail de choix des identifiants</a>
            <?php endif; ?>
        </div>
    </div>
        
    <div class="modification"<?php if (!$form->hasErrors()) echo ' style="display:none;"'; ?>>
    	<form method="post" action="<?php echo url_for('validation_compte', array('num_contrat' => $contrat->no_contrat)) ?>">
        <div class="bloc_form">
                <div class="ligne_form ">
                    <?php echo $form->renderHiddenFields(); ?>
                    <?php echo $form->renderGlobalErrors(); ?>

                    <label><?php echo $form['nom']->renderLabel() ?></label>
                    <?php echo $form['nom']->render() ?>
                    <?php echo $form['nom']->renderError() ?>
                </div>
                <div class="ligne_form ligne_form_alt">
                    <label><?php echo $form['prenom']->renderLabel() ?></label>
                    <?php echo $form['prenom']->render() ?>
                    <?php echo $form['prenom']->renderError() ?>
                </div>
                <div class="ligne_form ">
                    <label><?php echo $form['telephone']->renderLabel() ?></label>
                    <?php echo $form['telephone']->render() ?>
                    <?php echo $form['telephone']->renderError() ?>
                </div>
                <div class="ligne_form ligne_form_alt">
                    <label><?php echo $form['fax']->renderLabel() ?></label>
                    <?php echo $form['fax']->render() ?>
                    <?php echo $form['fax']->renderError() ?>
                </div>
                <div class="ligne_form ">
                    <label><?php echo $form['email']->renderLabel() ?></label>
                    <?php echo $form['email']->render() ?>
                    <?php echo $form['email']->renderError() ?>
                </div>
                <div class="ligne_form ligne_form_alt">
                    <label><?php echo $form['mdp1']->renderLabel() ?></label>
                    <?php echo $form['mdp1']->render() ?>
                    <?php echo $form['mdp1']->renderError() ?>
                </div>
                <div class="ligne_form">
                    <label><?php echo $form['mdp2']->renderLabel() ?></label>
                    <?php echo $form['mdp2']->render() ?>
                    <?php echo $form['mdp2']->renderError() ?>
                </div>
        </div>
        <div class="ligne_btn btn">
            <button class="btn_valider" type="submit"><span>Valider</span></button>
            <a href="#" class="btn_annuler">Annuler</a>
        </div>
        </form>
    </div>
</div>