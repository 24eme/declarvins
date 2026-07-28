<?php



$ecritures = [];

while($line = trim(fgets(STDIN))) {
    if(preg_match('/^code journal/', $line)) {
        continue;
    }
    $data = str_getcsv($line, ";");
    $codeCompte = $data[5];
    if($data[14] == "ECHEANCE") {
        $codeCompte = "411".$data[6];
    }
    $ecriture = [
        $data[1],
        "VTDRM",
        $codeCompte,
        $data[15],
        $data[14],
        "20%",
        "FR",
        $data[3]." - ".preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+)$/", "$3/$2/$1", $data[1])." - ".$data[15],
        $data[3],
        "%DEBIT%",
        "%CREDIT%",
        "",
        $data[18],
        "",
        "",
    ];
    $f = fopen('php://memory', 'r+');
    fputcsv($f, $ecriture, ";");
    rewind($f);
    $keyEcriture = stream_get_contents($f);
    if(!array_key_exists($keyEcriture, $ecritures)) {
        $ecritures[$keyEcriture] = ["%DEBIT%" => null, "%CREDIT%" => null];
    }
    if($data[9] == "DEBIT") {
        $ecritures[$keyEcriture]["%DEBIT%"] += floatval($data[10]);
    }

    if($data[9] == "CREDIT") {
        $ecritures[$keyEcriture]["%CREDIT%"] += floatval($data[10]);
    }
}
echo "Date;Code Journal;Numéro de Compte;Libellé de compte;Libellé de ligne;Taux de TVA du compte;Code pays du compte;Libellé de pièce;Numéro de pièce;Débit et/ou Crédit;Crédit;Famille de catégories;Catégorie;Identifiant de ligne;Identifiant de lettrage\n";
foreach($ecritures as $ligne => $values) {
    echo str_replace(array_keys($values), array_values($values), $ligne);
}
