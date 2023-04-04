<?php echo include_partial('Email/headerMail') ?>

Une DRM utilisant du volume bloqué en reserve interpro, vient d'être validée : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('drm_visualisation', array('sf_subject' => $drm), true); ?>"><?php echo $drm->_id ?></a><br /><br />
Il s'agit de la DRM <strong><?php echo $drm->getMois() ?>/<?php echo $drm->getAnnee() ?></strong> de l'opérateur <strong><?php echo ($drm->declarant->nom)? $drm->declarant->nom : $drm->declarant->raison_sociale; ?></strong> (<?php echo $drm->identifiant ?>).

<?php echo include_partial('Email/footerMail') ?>
