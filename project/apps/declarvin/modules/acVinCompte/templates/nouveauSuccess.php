<section id="contenu">
	<?php if($sf_user->hasFlash('create-compte')) : ?>
	<p>
	Votre compte est désormais actif. Vous pouvez profiter pleinement des services offerts par la plateforme Declarvins.net.<br />
	Pensez à bien conserver votre identifiant et votre mot de passe. Ils sont uniques, confidentiels et les seuls valables pour votre entreprise.<br />
	Les administrateurs du site n'y ont pas accès et vous devrez en régénérer un en cas de perte.<br /><br />
	Bonne navigation.<br /><br />
	L'équipe Declarvins.net
	</p>
	<?php else: ?>
	<form id="creation_compte" method="post" action="<?php echo url_for('compte_nouveau', array('nocontrat' => $contrat->no_contrat)) ?>">
		<?php echo $form->renderHiddenFields(); ?>
		<?php echo $form->renderGlobalErrors(); ?>
		<h1><strong>Création de compte</strong></h1>
		<p>
			Bonjour <?php echo $contrat->nom ?> <?php echo $contrat->prenom ?>,<br />
			Veuillez saisir ici vos identifiants et mots de passe qui vous serviront à vous connecter sur la plateforme déclarative des vins du Rhône, de Provence et du Sud-Est.
			<br /><br /><br /><br />
		</p>
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
		</div>
		<div class="ligne_btn">
			<button type="submit" class="btn_valider"><span>Valider</span></button>
		</div>
	</form>
	<?php endif; ?>
</section>