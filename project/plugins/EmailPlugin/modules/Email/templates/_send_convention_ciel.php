<?php echo include_partial('Email/headerMail') ?>

Bonjour,<br /><br />
Vous venez d'adhérer en ligne au service d'échange de données CIEL-Declarvins.net et nous vous remercions.<br />
Vous trouverez en pièce jointe en pdf :
<ul>
<li>La convention CIEL des douanes, à renvoyer signée par courrier à votre service des douanes habituels.</li>
<li>L'avenant au contrat d'inscription Declarvins, à renvoyer signé à votre interprofession par scan, fax ou courrier.</li>
</ul>
<br />
Pour toute question, n'hésitez pas à <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Bonne journée<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>