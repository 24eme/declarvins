<div class="popup_form">
<form method="post" action="<?php echo url_for('@admin_comptes') ?>">
    <div class="ligne_form ligne_form_label">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php echo $form['compte']->renderLabel() ?>
        <?php echo $form['compte']->render() ?>
        <?php echo $form['compte']->renderError() ?>
    </div>
	<br />
    <div class="btnValidation">
    	<span>&nbsp;</span>
        <input class="btn_valider" type="submit" value="Valider" />
    </div>
</form>
</div>
<script type="text/javascript">
$(document).ready(function () {
	$("#<?php echo $form['compte']->renderId() ?>").combobox();
});
</script>