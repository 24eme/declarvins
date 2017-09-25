<?php use_helper('Date'); ?>
<?php echo include_partial('Email/headerMail') ?>
<?php $drmCiel = $drm->getOrAdd('ciel'); ?>

Entreprise :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} ?><br /><br />
Madame, Monsieur,<br /><br />
Votre DRM du <strong><?php echo $drm->getMois() ?>/<?php echo $drm->getAnnee() ?></strong> n° <strong><?php echo $drmCiel->identifiant_declaration ?></strong> transmise par declarvins.net au téléservice CIEL des douanes le <strong><?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?></strong> à <strong><?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?></strong> a bien été validée et déposée.<br /><br />
Vous pourrez saisir la DRM suivante à partir du 25 de ce mois.<br /><br />
Pour toute question, n'hésitez pas à contacter votre interprofession.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>