<section id="contenu" style="padding: 30px 20px 70px;">
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
		<h1>Identifiants de connexion oubliés</h1>	
		<p class="txt-espace">
		Veuillez saisir l'identifiant ou l'e-mail de votre compte pour lancer une procédure de récupération d'identifiant et redéfinition de votre mot de passe.<br />
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
			<strong class="champs_obligatoires">* Champs obligatoires</strong>
		</div>
		<div class="ligne_btn">
			<button type="submit" class="btn_valider">Valider</button>
		</div>
		<?php if ($email_send): ?>
		<br />
		<div id="btn_etape_dr">
			<a href="<?php echo url_for('@ac_vin_logout') ?>" class="btn_prec"><span>Retour à la page de login</span></a>
		</div>
		<?php endif; ?>	
	</form>
</section>