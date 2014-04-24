<section id="contenu" style="padding: 30px 20px 70px;">
	<?php if($sf_user->hasFlash('create-compte')) : ?>
	<p>Votre compte est désormais actif. Vous pouvez profiter pleinement des services offerts par la plateforme <a href="<?php echo url_for('ac_vin_login') ?>" class="lien_declarvin">Declarvins.net</a>.</p>
	<p>Pensez à bien conserver votre identifiant et votre mot de passe. Ils sont uniques, confidentiels et les seuls valables pour votre entreprise.</p>
	<p>Les administrateurs du site n'y ont pas accès et vous devrez en régénérer un en cas de perte.</p><br />
	
	<p><a href="<?php echo url_for('tiers') ?>">Cliquez ici pour revenir sur la plateforme Declarvins.net.</a></p><br />
	
	<p>Bonne navigation.</p><br />
	<p>L'équipe Declarvins.net</p>
	<?php else: ?>
	<form id="creation_compte" method="post" action="<?php echo url_for('compte_nouveau', array('nocontrat' => $contrat->no_contrat)) ?>">
		<?php echo $form->renderHiddenFields(); ?>
		<?php echo $form->renderGlobalErrors(); ?>
		<h1><strong>Création de compte</strong></h1>
		<p>Bonjour <strong><?php echo $contrat->prenom ?> <?php echo $contrat->nom ?></strong>,</p><br />
		<p>Veuillez créer un identifiant de compte qui vous servira à vous connecter sur la plateforme déclarative des vins du Rhône, de Provence et du Sud-Est.</p>
		<br /><br />
		<div class="col">
			<div class="ligne_form">			
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
			<strong class="champs_obligatoires">* Champs obligatoires</strong>
		</div>
		<div class="ligne_btn">
			<button type="submit" class="btn_valider"><span>Valider</span></button>
		</div>
	</form>
	<?php endif; ?>
</section>