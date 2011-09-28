<h1>Etape 3 : Création de compte</h1>

<?php if($sf_user->hasFlash('notice')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>
<form method="post" action="<?php echo url_for('@compte_nouveau') ?>">
	<div class="ligne_form">
	<?php echo $form->renderHiddenFields(); ?>
	<?php echo $form->renderGlobalErrors(); ?>
	
	<?php echo $form['login']->renderError() ?>
	<?php echo $form['login']->renderLabel() ?>
	<?php echo $form['login']->render() ?>
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
	<div class="ligne_form">
	<?php echo $form['email1']->renderError() ?>
	<?php echo $form['email1']->renderLabel() ?>
	<?php echo $form['email1']->render() ?>
	</div>
	<div class="ligne_form">
	<?php echo $form['email2']->renderError() ?>
	<?php echo $form['email2']->renderLabel() ?>
	<?php echo $form['email2']->render() ?>
	</div>

	<div class="btn">
		<input type="submit" value="Valider" />
	</div>
</form>

