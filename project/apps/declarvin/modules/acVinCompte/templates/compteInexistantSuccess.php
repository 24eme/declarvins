<section id="contenu">
	<h1>Erreur lors de la connexion</h1>
	<p id="erreur_connexion">
	Vous tentez de vous connecter à la plateforme <a href="<?php echo url_for('ac_vin_login') ?>" class="lien_declarvin">Declarvins.net</a> avec un compte qui n'existe pas<?php if ($compte): ?> (<?php echo $compte ?>)<?php endif; ?>.<br />
	Si vous n'êtes pas encore inscrit, nous vous invitons à créer votre compte en suivant le lien suivant : <a href="<?php echo url_for('@homepage') ?>">Inscription à la plateforme Declarvins.net</a>.<br />
	Merci de bien vouloir contacter votre interpro si cela vous pose problème.<br /><br />
	Bonne navigation.<br /><br />
	L'équipe Declarvins.net
	</p>
</section>