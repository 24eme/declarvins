<form id="formLiaisonInterpro" method="post" action="<?php echo url_for('validation_liaison', array('num_contrat' => $contrat->no_contrat)) ?>">
    <div id="liaisonInterpro" class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['interpro']->renderError() ?>
        <?php echo $form['interpro']->render() ?>
    </div>
    <div class="ligne_form_btn">
		<button class="btn_valider" type="submit"><span>Lier/Délier</span></button>
	</div>
</form> 