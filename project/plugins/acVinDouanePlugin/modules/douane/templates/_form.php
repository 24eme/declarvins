<?php echo $form->renderGlobalErrors() ?>
<?php echo $form->renderHiddenFields() ?>
<div class="contenu_onglet">
	<div class="ligne_form ">
		<span class="error"><?php echo $form['nom']->renderError() ?> </span>
		<?php echo $form['nom']->renderLabel() ?>
		<?php echo $form['nom']->render() ?>
	</div>
	<br />
	<div class="ligne_form ">
		<span class="error"><?php echo $form['identifiant']->renderError() ?> </span>
		<?php echo $form['identifiant']->renderLabel() ?>
		<?php echo $form['identifiant']->render() ?>
	</div>
	<br />
	<div class="ligne_form ">
		<span class="error"><?php echo $form['email']->renderError() ?> </span>
		<?php echo $form['email']->renderLabel() ?>
		<?php echo $form['email']->render() ?>
	</div>
	<br />
	<div class="ligne_form_btn">
		<a name="annuler" class="btn_annuler btn_fermer" href="<?php echo url_for('@admin_douanes') ?>">Annuler</a>
		<button name="valider" class="btn_valider" type="submit">Valider</button>
	</div>
</div>
