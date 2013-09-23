<?php echo include_partial('Email/headerMail') ?>

Entreprise :  <?php echo ($etablissement->raison_sociale)? $etablissement->raison_sociale : $etablissement->nom; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
Le contrat de transaction saisi le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?> vous concernant a été validé par toutes les parties et enregistré par votre interprofession qui vous attribue un VISA/numéro de contrat.<br />
Vous trouverez ci-joint une version pdf avec le numéro de contrat correspondant.<br />
Nous vous invitons à bien conserver ce document, preuve de la transaction passée entre les différentes parties.<br />
Il sera également accessible dans votre historique des contrats dans DeclarVins.net.<br /><br />
Pour toute information, vous pouvez <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>