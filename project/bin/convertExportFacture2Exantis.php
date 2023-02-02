<?php

function initFactureTab($tabLine) {
    return [
      "NumeroDocument" => $tabLine[3],
      "DomaineDocument" => "V",
      "TypeDocument" => 7,
      "Devise" =>"EUR",
      "DateDocument" => date('d/m/Y', strtotime($tabLine[1])),
      "NumeroClientOrigine" => $tabLine[16],
      "NumeroClientDestination" => null,
      "NomClientDestination" => $tabLine[15],
      "Addresse1ClientDestination" => null,
      "Addresse2ClientDestination" => null,
      "CodePostalClientDestination" => null,
      "VilleClientDestination" => null,
      "CompteGeneralClientDestination" => null,
      "NumeroPayeurClientDestination" => null,
      "CodeDepotDepartMarchandises" => null,
      "CodeDepotClientDestination" => (substr($tabLine[19], -3, 1) == '-')? substr($tabLine[19], 4, -8) : substr($tabLine[19], 4, -12),
      "DateLivraison" => null,
      "CodeAffaire" => null,
      "NomTransporteur" => null,
      "ModeExpedition" => null,
      "ReferenceDocument" => "DRM",
      "TarifDocument" => null,
      "TauxEscompteDocument" => null,
      "CategorieComptable" => null,
      "NomCommercial" => null,
      "PrenomCommercial" => null,
      "TotalHTDocument" => 0,
      "TotalTTCDocument" => 0,
      "NombreLignesDocument" => 0,
      "DocumentLigne" => [],
      "NombreEcheance" => 1,
  	  "Echeances" => [[
          "DateEcheance" => null,
          "LibelleModeReglement" => "Cheque",
  		  "MontantEcheance" => 0
  	  ]]
    ];
}

function getDetail($tabLine) {
    $pos = strpos($tabLine[4], '-');
    if (substr($tabLine[19], -3, 1) == '-') {
        $annee = substr($tabLine[19], -7, 4);
        $mois = substr($tabLine[19], -2);
        $version = "000";
    } else {
        $annee = substr($tabLine[19], -11, 4);
        $mois = substr($tabLine[19], -6, 2);
        $version = substr($tabLine[19], -3);
    }
    return [
        "CodeArticle" => getCodeArticle($tabLine[4]),
        "Designation" => ($pos === false)? $tabLine[4] : trim(substr($tabLine[4], $pos+1)),
        "CodeAffaire" => null,
        "QteColisee" => null,
        "LibelleColisage" => null,
        "Qte" => floatval($tabLine[20]),
        "MontantHT" => round(floatval($tabLine[10]), 2),
        "TXcvo" => floatval($tabLine[21]),
        "AnneeDRM" => $annee,
        "MoisDRM" => $mois,
        "RectifModif" => $version,
        "TauxRemise" => 0,
        "TauxTaxe" => 20,
        "PoidsBrut" => null,
        "PoidsNet" => null,
        "Lot" => null,
        "PeremptionLot" => null,
    ];
}

function getCodeArticle($designation) {
    //TODO faire la correspondance
    return null;
}



$factures = [];
while($line = fgets(STDIN)) {
    if (substr($line, 0, 3) != 'VEN') continue;
    $tabLine = explode(';', $line);
    if (!isset($factures[$tabLine[3]])) $factures[$tabLine[3]] = initFactureTab($tabLine);
    if ($tabLine[14] == 'TVA') {
        $factures[$tabLine[3]]["TotalHTDocument"] = round($factures[$tabLine[3]]["TotalHTDocument"] - floatval($tabLine[10]), 2);
        continue;
    }
    if ($tabLine[14] == 'ECHEANCE') {
        $factures[$tabLine[3]]["TotalHTDocument"] = round($factures[$tabLine[3]]["TotalHTDocument"] + floatval($tabLine[10]), 2);
        $factures[$tabLine[3]]["TotalTTCDocument"] = round($factures[$tabLine[3]]["TotalTTCDocument"] + floatval($tabLine[10]), 2);
        $factures[$tabLine[3]]["Echeances"][0]["MontantEcheance"] = round($factures[$tabLine[3]]["Echeances"][0]["MontantEcheance"] + floatval($tabLine[10]), 2);
        continue;
    }
    if ($tabLine[14] == 'LIGNE') {
        $factures[$tabLine[3]]["NombreLignesDocument"]++;
        $factures[$tabLine[3]]["DocumentLigne"][] = getDetail($tabLine);
    }
}

echo json_encode(array_values($factures), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)."\n";
