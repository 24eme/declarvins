<form method="post" action="<?php echo url_for('@etablissement_login') ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['etablissement']->renderLabel() ?>
        <?php echo $form['etablissement']->render() ?>
        <?php echo $form['etablissement']->renderError() ?>
    </div>
	<br />
    <div class="btnValidation">
    	<span>&nbsp;</span>
        <input class="btn_valider" type="submit" value="Valider" />
    </div>
</form>
<script type="text/javascript">
$(document).ready(function () {
	$( "#<?php echo $form['etablissement']->renderId() ?>" ).combobox();
});
</script>