<h1>Etape 1 : Création de compte</h1>
<?php if($sf_user->hasFlash('notice')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>
<form method="post" action="<?php echo url_for('@compte_nouveau') ?>">
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
	<div class="ligne_form">
	<?php echo $form['fonction']->renderError() ?>
	<?php echo $form['fonction']->renderLabel() ?>
	<?php echo $form['fonction']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['telephone']->renderError() ?>
	<?php echo $form['telephone']->renderLabel() ?>
	<?php echo $form['telephone']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['fax']->renderError() ?>
	<?php echo $form['fax']->renderLabel() ?>
	<?php echo $form['fax']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['login']->renderError() ?>
	<?php echo $form['login']->renderLabel() ?>
	<?php echo $form['login']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['email']->renderError() ?>
	<?php echo $form['email']->renderLabel() ?>
	<?php echo $form['email']->render() ?>
	</div>
	<div class="ligne_form">
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
		<input type="submit" value="Créer" />
	</div>
</form>
