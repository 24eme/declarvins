<div class="popup_form">
<form method="post" action="<?php echo url_for($route) ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['identifiant']->renderLabel() ?>
        <?php echo $form['identifiant']->render() ?>
        <?php echo $form['identifiant']->renderError() ?>

        <input class="btn_valider" type="submit" value="Valider" />
    </div>
</form>
</div>
<script type="text/javascript">
$(document).ready(function () {
    $("#bloc_admin_etablissement_choice").hide();
	$("#<?php echo $form['identifiant']->renderId() ?>").combobox();
});
</script>
