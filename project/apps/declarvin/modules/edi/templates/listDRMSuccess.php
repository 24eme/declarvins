<?php
echo "#identifiant;annee;mois;drm validee;drm envoyée aux douanes;drm recues douanes\n";
foreach($historique->getDrms() as $drm) {
  echo $drm[0].';'.$drm[1].';'.$drm[2].';'.$drm[3].';'.$drm[4].';'.$drm[5]."\n";
 }