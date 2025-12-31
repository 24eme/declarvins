<?php echo include_partial('Email/headerMail') ?>

Entreprise num. <strong><?php echo $etablissement->identifiant ?></strong> :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Vous n'avez pas déposé le fichier DTI+ de commercialisation pour le dernire mois.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>
