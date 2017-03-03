<?php use_helper('Date'); ?>
<?php echo include_partial('Email/headerMail') ?>

<?php $drmCiel = $drm->getOrAdd('ciel'); ?>

Entreprise :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
Votre DRM du <strong><?php echo $drm->getMois() ?>/<?php echo $drm->getAnnee() ?></strong> n° <strong><?php echo $drmCiel->identifiant_declaration ?></strong> a bien été transmise par declarvins.net au téléservice CIEL des douanes le <strong><?php echo format_date($drmCiel->horodatage_depot, 'dd/MM/yyyy') ?></strong> à <strong><?php echo format_date($drmCiel->horodatage_depot, 'H:m') ?></strong>.<br /><br />
CIEL nous indique que vous avez modifié votre DRM avant de la valider.<br />
Vous trouverez ci-dessous les différences constatées :<br />
<ul>
<?php foreach ($diffs as $k => $v): ?>
	<li><?php echo $k ?> => <?php echo $v ?></li>
<?php endforeach; ?>
</ul>
<br />
Afin de conserver une cohérence des données et éviter tout blocage pour votre DRM du mois prochains, il faut vous connecter à declarvins.net afin de mettre à jour votre DRM rectificative avec les modifications faites sur CIEL.<br /><br />
Votre interprofession reste à votre disposition pour plus d'information.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>