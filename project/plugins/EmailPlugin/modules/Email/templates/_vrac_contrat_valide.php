<?php echo include_partial('Email/headerMail') ?>

Madame, Monsieur,<br /><br />
Le <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', array('sf_subject' => $vrac, 'etablissement' => $etablissement), true); ?>">contrat de transaction en <?php echo strtolower($vrac->type) ?></a> saisi le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> vous concernant a été validé par toutes les parties.<br />
Vous trouverez ci-joint une version pdf avec le numéro de contrat correspondant.<br />
Nous vous invitons à bien conserver ce document, preuve de la transaction passée entre les différentes parties.<br />
Il sera également accessible dans votre historique des contrats dans declarvins.net.<br /><br />
Pour toute information, vous pouvez contacter votre interprofession.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>