<?php use_helper('Date'); ?>
<?php echo include_partial('Email/headerMail') ?>
<?php $drmCiel = $drm->getOrAdd('ciel'); ?>

Entreprise :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
Votre DRM du <strong><?php echo $drm->getMois() ?>/<?php echo $drm->getAnnee() ?></strong> n° <strong><?php echo $drmCiel->identifiant_declaration ?></strong> a bien été transmise au téléservice CIEL des douanes le <strong><?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?></strong> à <strong><?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?></strong>.<br /><br />
A ce jour et sauf erreur de notre part, nous n'avons pas eu de confirmation de la validation de votre DRM sur CIEL. Nous vous engageons donc à vérifier que votre DRM soit validée ("déposer la DRM") sur le site Prodouane via le lien suivant : <a href="https://pro.douane.gouv.fr/">pro.douane.gouv.fr</a> en vous connectant et en allant sur l'interface CIEL (menu de gauche).<br /><br />
Votre interprofession reste à votre disposition pour plus d'information.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>