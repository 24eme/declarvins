<?php use_helper('Date'); ?>
<?php echo include_partial('Email/headerMail') ?>

<?php $drmCiel = $drm->getOrAdd('ciel'); ?>

Entreprise :  <?php if($etablissement->nom) { echo $etablissement->nom; } if($etablissement->raison_sociale) { echo ($etablissement->nom)? ' / '.$etablissement->raison_sociale : $etablissement->raison_sociale; } echo ($etablissement->famille)? ' - '.ucfirst($etablissement->famille) : ''; ?><?php if ($etablissement->telephone) {echo ' '.$etablissement->telephone;} if ($etablissement->fax) {echo ' '.$etablissement->fax;} if ($etablissement->email) {echo ' '.$etablissement->email;} ?><br /><br />
Madame, Monsieur,<br /><br />
La plateforme CIEL de la Douane, nous indique que vous avez apporté des corrections à votre DRM du <strong><?php echo $drm->getMois() ?>/<?php echo $drm->getAnnee() ?></strong>.Pour la consulter, veuillez suivre ce lien :<br />
<a href="<?php echo ProjectConfiguration::getAppRouting()->generate('drm_visualisation', array('sf_subject' => $drm), true); ?>">Consulter la DRM</a>.
<br /><br />
Merci de bien vouloir apporter les mêmes corrections à votre DRM saisie sur DeclarVins afin de conserver une cohérence des mouvements et éviter tout blocage lors de la déclaration de votre DRM du mois prochain. Pour cela, il faut vous connecter à declarvins.net afin de mettre à jour votre DRM rectificative, ouverte automatiquement, en appliquant les rectifications faites sur CIEL.<br /><br />
Vous trouverez ci-dessous les différences constatées :<br />
<ul>
<?php foreach ($diffs as $k => $v): ?>
	<li><?php echo $k ?> => <?php echo $v ?></li>
<?php endforeach; ?>
</ul>
<br /><br />
Votre interprofession reste à votre disposition pour plus d'information.<br /><br />
Cordialement,<br /><br />
L'équipe Declarvins.net

<?php echo include_partial('Email/footerMail') ?>
