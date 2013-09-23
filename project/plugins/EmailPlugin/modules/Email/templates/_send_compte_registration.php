<?php echo include_partial('Email/headerMail') ?>
 	
Bonjour,<br /><br />
Suite à votre inscription en ligne sur le site Declarvins.net vos données ont été mises à jour et votre compte a bien été créé.<br />
Cet e-mail vaut engagement de l'interprofession pour le contrat d'inscription et les Avenants valides à date d'aujourd'hui.<br />
Afin d'activer votre compte, veuillez créer votre identifiant et votre mot de passe en cliquant sur le lien suivant : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('compte_nouveau', array('nocontrat' => $numero_contrat), true); ?>">Création de mes identifiants</a><br /><br />
Attention, vous êtes responsable de la gestion de votre compte Déclarant et de sa mise à jour : nom, prénom, fonction, e-mail, et Etablissements (Opérateurs) gérés par ce compte ; ainsi que de la gestion de l'identifiant et du mot de passe associés.<br /><br />
Pour toute question, n'hésitez pas à <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Bonne journée<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>