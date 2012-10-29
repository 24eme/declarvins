<?php
echo "#identifiant;annee;mois;rectificative;drm validee;drm envoyée aux douanes;drm recues douanes\n";
foreach($historiques as $historique) {
  foreach($historique->getDRMs() as $drm) {
    echo $drm->identifiant.';'.DRMClient::getInstance()->getAnnee($drm->periode).';'.DRMClient::getInstance()->getMois($drm->periode).';'.DRMClient::getInstance()->getRectificative($drm->version).';'.$drm->valide->date_saisie.';'.$drm->douane->envoi.';'.$drm->douane->accuse."\n";
  }
}