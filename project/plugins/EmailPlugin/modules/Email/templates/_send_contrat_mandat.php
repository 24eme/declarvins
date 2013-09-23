<?php echo include_partial('Email/headerMail') ?>

Bonjour,<br /><br />
Vous venez de vous inscrire en ligne sur le site Declarvins.net et nous vous remercions.<br />
Vous trouverez en pièce jointe en pdf un contrat d'inscription à imprimer et à retourner à votre interprofession avec votre K-Bis.<br />
En parallèle, votre interprofession vous fait parvenir par mail le cas échéant les avenants précisant les services disponibles, et notamment les contrats mandats de dépôts mis en place.<br />
Une fois que celle-ci aura reçu le contrat d'inscription et mis à jour vos données et votre compte, vous recevrez un autre mail afin de créer votre identifiant et votre mot de passe et d'activer définitivement votre compte.<br /><br />
Pour toute question, n'hésitez pas à <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Bonne journée<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>