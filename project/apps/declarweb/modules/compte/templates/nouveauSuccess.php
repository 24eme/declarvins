<section id="contenu">
<?php if($sf_user->hasFlash('notice')) : ?>
<p class="flash_message"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>
<form id="creation_compte" method="post" action="<?php echo url_for('@compte_nouveau') ?>">
	<h1>Etape 3 : <strong>Création de compte</strong></h1>
	<div class="col">
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
		<?php echo $form['email']->renderError() ?>
		<?php echo $form['email']->renderLabel() ?>
		<?php echo $form['email']->render() ?>
		</div>
		<div class="ligne_form">
		<?php echo $form['email2']->renderError() ?>
		<?php echo $form['email2']->renderLabel() ?>
		<?php echo $form['email2']->render() ?>
		</div>
	</div>
					
	<div class="ligne_btn">
		<button type="submit" class="btn_valider">Valider</button>
	</div>
</form>
</section>
<!-- fin #contenu -->

