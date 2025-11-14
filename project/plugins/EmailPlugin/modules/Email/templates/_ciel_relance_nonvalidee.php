<?php use_helper('Date'); ?>
<?php echo include_partial('Email/headerMail') ?>
<?php $drmCiel = $drm->getOrAdd('ciel'); ?>

Entreprise :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
Nous vous contactons pour vous attirer votre attention sur le fait que votre DRM que vous avez saisie n'a pas encore été validée. Elle aurait du l'être avant le 10 du mois.<br/>
Vous pouvez terminer votre saisie en vous rendant dans votre <a href="https://declaration.declarvins.net/drm/<?php echo $etablissement->identifiant; ?>">espace DRM sur DéclarVins</a><br/><br/>
Si vous ne l'avez pas fait car vous rencontrez un problème, n'hésitez pas à contacter votre interprofession.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>
