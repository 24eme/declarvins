<?php
echo "#identifiant;annee;mois;rectificative;drm validee;drm envoyée aux douanes;drm recues douanes\n";
foreach($historiques as $historique) {
  foreach($historique->getDrms() as $drm) {
    echo $drm[0].';'.$drm[1].';'.$drm[2].';'.$drm[3].';'.$drm[4].';'.$drm[5].';'.$drm[6]."\n";
  }
}