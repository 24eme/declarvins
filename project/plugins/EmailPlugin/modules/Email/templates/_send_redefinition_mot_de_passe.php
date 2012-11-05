<?php echo include_partial('Email/headerMail') ?>
 	
Une procédure de redéfinition de mot de passe pour votre compte a été demandée.<br />
Vous pouvez le modifier en suivant le lien suivant : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('compte_password', array('login' => $compte->login), true); ?>">Redéfinition de mon mot de passe</a>

<?php echo include_partial('Email/footerMail') ?>