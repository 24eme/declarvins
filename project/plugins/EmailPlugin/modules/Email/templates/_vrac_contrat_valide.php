<?php echo include_partial('Email/headerMail') ?>

Entreprise :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
<?php if ($vrac->isRectificative()): ?>
Le contrat de transaction numéro <?php echo $vrac->numero_contrat; ?> et saisi le <strong><?php echo strftime('%d/%m/%Y', strtotime($vrac->getMaster()->valide->date_saisie)) ?> a été rectifié le <?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?></strong> à la demande de toutes les parties au contrat.<br />
<?php else: ?>
Le contrat de transaction saisi le <strong><?php echo strftime('%d/%m/%Y', strtotime($vrac->valide->date_saisie)) ?></strong> vous concernant a été validé par toutes les parties et enregistré par votre interprofession qui vous attribue un VISA/numéro de contrat.<br />
<?php endif; ?>
Vous trouverez ci-joint une version pdf avec le numéro de contrat correspondant.<br />
Nous vous invitons à bien conserver ce document, preuve de la transaction passée entre les différentes parties.<br />
Il sera également accessible dans votre historique des contrats dans DeclarVins.net.<br /><br />
Pour toute information, vous pouvez <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('contact', array(), true); ?>">contacter votre interprofession</a><br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net 

<?php echo include_partial('Email/footerMail') ?>