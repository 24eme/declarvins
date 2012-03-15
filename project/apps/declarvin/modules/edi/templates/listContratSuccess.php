<?php
echo "#identifiant;numero de contrat;certification;appellation;lieu,couleur;cepage;millesime;labels;mention;nom acheteur;volume promis;volume realise;\n";
foreach($contrats as $vrac) {
  echo $vrac->etablissement.';'.$vrac->_id.';';
  $libelles = $vrac->getRawValue()->getProduitConfiguration()->getLibelles();
  for ($i = count($libelles) ; $i <= 7 ; $i++) {
    $libelles[$i] = '';
  }
  echo implode (';', $libelles).';';
  echo $vrac->acheteur->nom.';'.$vrac->volume_promis.';'.$vrac->volume_realise.";\n";
  }