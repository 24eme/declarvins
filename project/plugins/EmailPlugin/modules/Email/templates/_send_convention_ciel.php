<?php echo include_partial('Email/headerMail') ?>

Bonjour,<br /><br />
Vous venez d'adhérer en ligne au service d'échange de données CIEL-Declarvins.net et nous vous remercions.<br />
Vous trouverez en pièce jointe en pdf la convention d'adhésion à imprimer et à retourner signée à votre service douane et votre interprofession.<br />
Pour toute question, n'hésitez pas à <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Bonne journée<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>