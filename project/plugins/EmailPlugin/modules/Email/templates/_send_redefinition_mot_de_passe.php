<?php echo include_partial('Email/headerMail') ?>
 	
Bonjour,<br /><br />
Vous souhaitez créer un nouveau mot de passe pour accéder à la plateforme Declarvins.net.<br /><br />
Merci de suivre la procédure suivante en cliquant sur ce lien : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('compte_password', array('login' => $compte->login), true); ?>">Redéfinition de mon mot de passe</a><br /><br />
N'hésitez pas à contacter votre interprofession pour plus d'informations.<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>