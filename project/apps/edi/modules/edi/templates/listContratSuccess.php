<?php
echo "#identifiant;numero de contrat;certification;appellation;mention;lieu,couleur;cepage;millesime;labels;mention;nom acheteur;nom courtier;volume promis;volume realise;\n";
foreach($contrats as $vrac) {
  echo $vrac->etablissement.';'.$vrac->numero.';';
  $libelles = array(); //$vrac->getRawValue()->getProduitConfiguration()->getLibelles();
  for ($i = count($libelles) ; $i <= 8 ; $i++) {
    $libelles[$i] = '';
  }
  echo implode (';', $libelles).';';
  echo $vrac->acheteur->nom.';;'.$vrac->volume_promis.';'.$vrac->volume_realise.";\n";
  }