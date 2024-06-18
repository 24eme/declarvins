<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<div id="contenu" class="vracs">
		<form action="<?php echo url_for('vrac_commentaire', array('sf_subject' => $vrac, 'etablissement' => $etablissement)); ?>" method="post" id="vrac_condition">
            <?php echo $form->renderHiddenFields() ?>
            <?php echo $form->renderGlobalErrors() ?>
            <?php echo $form['commentaire_annulation']->renderError() ?>
            <h1><?php echo $form['commentaire_annulation']->renderLabel() ?></h1>
            <p id="titre" style="text-align: left; margin-bottom: 30px;">
                Afin d'annuler ce contrat, veuillez renseigner la raison de l'annulation.
            </p>
            <?php echo $form['commentaire_annulation']->render() ?>
            <div class="ligne_form_btn">
                <button type="submit" class="annuler_saisie pull-right" onclick="return confirm('Confirmez-vous le refus du contrat?')" id="btn_annuler_contrat">Refuser</button>
            </div>
    </form>
</div>
