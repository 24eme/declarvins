<?php echo include_partial('Email/headerMail') ?>
 	
Bonjour,<br /><br />
Suite à votre inscription en ligne sur le site Declarvins.net vos données ont été mises à jour et votre compte a bien été créé.<br />
Afin d'activer votre compte, veuillez créer votre identifiant et votre mot de passe en cliquant sur le lien suivant : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('compte_nouveau', array('nocontrat' => $numero_contrat), true); ?>">Création de mes identifiants</a><br /><br />
Pour toute question, n'hésitez pas à contacter votre interprofession,<br /><br />
Bonne journée<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>