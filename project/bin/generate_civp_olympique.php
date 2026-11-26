<?php

if (($handle = fopen(__DIR__."/../web/exports_civp/export_bi/export_bi_mouvements.csv", "r")) === false) {
//if (($handle = fopen("php://stdin", "r")) === false) {
    echo "ERROR: export_bi_mouvements.csv non trouvable\n";
    exit(1);
}
$row = 1;
$olympique = [];
$year = date('Y') - 1;
while (($fields = fgetcsv($handle, 1000, ";", "\"", "\\")) !== false) {
    $row++;
    $fields['volume'] = $fields[19];
    $fields['cvo'] = $fields[24];
    $fields['facturable'] = $fields[25];
    $fields['mouvement'] = $fields[30];
    $fields['appellation'] = $fields[8];
    $fields['couleur'] = $fields[11];
    $fields['identifiant'] = $fields[2];
    $fields['nom'] = $fields[16];
    $fields['campagne'] = $fields[3];
    $fields['famille'] = $fields[33];
    $identifiant = $fields['identifiant'];

    $diff_year = $year - intval($fields['campagne']);
    if ($diff_year > 4 || $diff_year < 0) {
        continue;
    }

    $doctype = 'drm_sortie';
    if (strpos($fields['mouvement'], 'sortie') === false) {
        if (strpos($fields['mouvement'], 'recolte') === false) {
            continue;
        }
        $doctype = 'drm_recolte';
    }
    if ($doctype == 'drm_sortie' && $fields['cvo'] <= 0) {
        continue;
    }
    if ($doctype == 'drm_sortie' && $fields['facturable'] <= 0) {
        continue;
    }
    if (!isset($olympique[$identifiant])) {
        $olympique[$identifiant] = array('identifiant' => $identifiant, 'famille' => $fields['famille'], 'nom' => $fields['nom'], 'docs' => array($doctype => ['produits' => []]));
    }
    $produit = $fields['appellation'].'/'.$fields['couleur'];
    if (!isset($olympique[$identifiant]['docs'][$doctype]['produits'][$produit])) {
        $olympique[$identifiant]['docs'][$doctype]['produits'][$produit] = array();
    }
    if (!isset($olympique[$identifiant]['docs'][$doctype]['produits'][$produit][$fields['campagne']])) {
        $olympique[$identifiant]['docs'][$doctype]['produits'][$produit][$fields['campagne']] = 0;
    }
    $olympique[$identifiant]['docs'][$doctype]['produits'][$produit][$fields['campagne']] += $fields['volume'];
}

foreach($olympique as $identifiant => $obj) {
    foreach ($obj['docs'][$doctype]['produits'] as $produit => $campagnes) {
        if (count($campagnes) < 5) {
            unset($olympique[$identifiant]['docs'][$doctype]['produits'][$produit]);
            continue;
        }
        $min_key = array_search(min($campagnes),$campagnes);
        $max_key = array_search(max($campagnes),$campagnes);
        unset($olympique[$identifiant]['docs'][$doctype]['produits'][$produit][$min_key]);
        unset($olympique[$identifiant]['docs'][$doctype]['produits'][$produit][$max_key]);
        $olympique[$identifiant]['docs'][$doctype]['produits'][$produit]['moyenne_olympique'] = array_sum($olympique[$identifiant]['docs'][$doctype]['produits'][$produit]) / count($olympique[$identifiant]['docs'][$doctype]['produits'][$produit]);
    }
}
echo "identifiant;produit;origine;campagne;volume\n";
foreach($olympique as $identifiant => $obj) {
    foreach ($obj['docs'] as $doctype => $docs) {
        foreach($docs['produits'] as $produit => $campagnes) {
            foreach ($campagnes as $campagne => $value) {
                echo "$identifiant;".$obj['nom'].";".$obj['famille'].";$produit;$doctype;$campagne;$value\n";
            }
        }
    }
}
