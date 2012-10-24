<form method="post" action="<?php echo url_for('@alertes') ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>
    <div class="ligne_form ligne_form_label">
        <?php echo $form['etablissement']->renderLabel() ?>
        <?php echo $form['etablissement']->render() ?>
        <?php echo $form['etablissement']->renderError() ?>
    </div>
    <div class="ligne_form ligne_form_label">
        <?php echo $form['campagne']->renderLabel() ?>
        <?php echo $form['campagne']->render() ?>
        <?php echo $form['campagne']->renderError() ?>
    </div>
    <div class="btnValidation">
    	<span><a href="<?php echo url_for('@alertes?reset_filters=true') ?>">Vider</a></span>
        <input class="btn_valider" type="submit" value="Filtrer" />
    </div>
</form>
<script type="text/javascript">
$(document).ready(function () {
	$("#<?php echo $form['etablissement']->renderId() ?>").combobox();
});
</script>