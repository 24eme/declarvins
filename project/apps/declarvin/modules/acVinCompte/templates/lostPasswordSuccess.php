<section id="contenu">
	<?php $email_send = false; ?>
	<?php if($sf_user->hasFlash('notice')) : ?>
	<?php $email_send = true; ?>
	 <div id="flash_message">
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice'); ?></div>
    </div>
	<?php endif; ?>
	<p></p>
	<form id="creation_compte" method="post" action="<?php echo url_for('@compte_lost_password') ?>">
		<?php echo $form->renderHiddenFields(); ?>
		<?php echo $form->renderGlobalErrors(); ?>
		<h1>Mot de passe oublié</h1>		
		<p class="txt-espace">
		Veuillez saisir l'identifiant de votre compte pour lancer une procédure de redéfinition de votre mot de passe.<br />
		Vous recevrez un email afin de modifier votre mot de passe.<br /><br />
		Si vous n'avez pas reçu d'email :
		<ul>
        	<li>Vérifiez vos spams.</li>
            <li>Contacter votre interprofession référente.</li>
		</ul>
		<br /><br />
		<div class="col">
			<div class="ligne_form">
				<?php echo $form['login']->renderError() ?>
				<?php echo $form['login']->renderLabel() ?>
				<?php echo $form['login']->render() ?>
			</div>
		</div>
		<div class="col">
			<div class="ligne_btn">
				<?php if ($email_send): ?>
				<a href="<?php echo url_for('@ac_vin_login') ?>">Retour à la page de login</a>
				<?php endif; ?>
				<button type="submit" class="btn_valider">Valider</button>
			</div>
		</div>
	</form>
</section>