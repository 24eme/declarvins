<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Vous êtes vendeurs de raisins.<br /><br />
Depuis le 1er janvier 2023, notre accord interprofessionnel prévoit pour l’ensemble des appellations en AOC Côtes du Rhône et Vallée du Rhône le suivi des transactions portant sur les achats de raisins.<br /><br />
Ces informations seront récupérées par le biais des contrats de raisins enregistrés sur notre plateforme Declarvins par votre acheteur (négociant vinificateur)<br /><br />
Tous les contrats saisis par votre acheteur, vont nécessités une validation de votre part.<br /><br  />
Pour cela, vous devez activer votre compte sur notre plateforme Declarvins, en créant votre identifiant et votre mot de passe en cliquant sur le lien suivant : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('compte_nouveau', array('nocontrat' => $numero_contrat), true); ?>">Création de mes identifiants</a><br /><br />
Nous restons à votre entière disposition, n’hésitez pas à nous contacter pour un accompagnement ou pour plus d’information.<br /><br />
Service Gestion du Vignoble.<br />
Tél 04 90 27 24 00<br />
Adresse mail : declarvins@intre-rhone.com.<br />

<?php echo include_partial('Email/footerMail') ?>
