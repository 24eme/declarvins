<?php
require_once(dirname(__FILE__).'/../lib/vendor/symfony/lib/yaml/sfYamlParser.php');

if (!isset($argv[1])) {
  echo "ERREUR : l'identifiant de la configuration produit a utiliser n'est pas spécifié\n";
  exit;
}

function initFactureTab($tabLine) {
    $ref = substr($tabLine[19], 0, strpos($tabLine[19], '-'));
    if ($tabLine[0] == 'ATT') $ref .= 'ATT';
    return [
      "NumeroDocument" => $tabLine[3],
      "DomaineDocument" => "V",
      "TypeDocument" => ($tabLine[0] == 'VEN')? 7 : 1,
      "Devise" =>"EUR",
      "DateDocument" => date('d/m/Y', strtotime($tabLine[1])),
      "NumeroClientOrigine" => $tabLine[16],
      "NomClientDestination" => $tabLine[15],
      "CodeDepotClientDestination" => $tabLine[27],
      "ReferenceDocument" => $ref,
      "TotalHTDocument" => 0,
      "TotalTTCDocument" => 0,
      "NombreLignesDocument" => 0,
      "DocumentLigne" => [],
      "NombreEcheance" => 0,
  	  "Echeances" => []
    ];
}

function getDetail($tabLine) {
    $pos = strpos($tabLine[4], '-');
    if (strpos($tabLine[19], 'SV12') !== false) {
        if (substr($tabLine[19], -5, 1) == '-') {
            $annee = substr($tabLine[19], -9, 4);
            $mois = null;
            $version = "000";
        } else {
            $annee = substr($tabLine[19], -13, 4);
            $mois = null;
            $version = substr($tabLine[19], -3);
        }
    } else {
        if (substr($tabLine[19], -3, 1) == '-') {
            $annee = substr($tabLine[19], -7, 4);
            $mois = substr($tabLine[19], -2);
            $version = "000";
        } else {
            $annee = substr($tabLine[19], -11, 4);
            $mois = substr($tabLine[19], -6, 2);
            $version = substr($tabLine[19], -3);
        }
    }
    return [
        "CodeArticle" => getCodeArticle($tabLine[4]),
        "Designation" => ($pos === false)? $tabLine[4] : trim(substr($tabLine[4], $pos+1)),
        "Qte" => round(floatval($tabLine[20]),2),
        "MontantHT" => round(floatval($tabLine[10]), 2),
        "TXcvo" => floatval($tabLine[21]),
        "AnneeDRM" => $annee,
        "MoisDRM" => $mois,
        "RectifModif" => $version,
        "TauxTaxe" => 20,
    ];
}

function getCodeArticle($designation) {
    global $appellations;
    if (!$appellations) return null;
    foreach($appellations as $appellation => $code) {
        if (strpos($designation, $appellation) !== false) return $code;
    }
    return null;
}

function getAppellations() {
    global $argv;
    $appellations = [];
    $couleurs = [];
    $databases = file_get_contents(dirname(__FILE__).'/../config/databases.yml');
    if ($databases) {
        $ymlParser = new sfYamlParser();
        $db = null;
        try {
            $db = $ymlParser->parse($databases);
        } catch(Exception $e) {
            return null;
        }
        if ($conf = file_get_contents($db['all']['default']['param']['dsn'].$db['all']['default']['param']['dbname'].'/'.$argv[1])) {
            $confObj = json_decode($conf);
            if (!is_object($confObj)) return null;
            $certifications = $confObj->declaration->certifications;
            foreach($certifications as $certification) {
                foreach($certification->genres as $genre) {
                    foreach($genre->appellations as $appellation) {
                      $appellations[$certification->libelle.' '.$genre->libelle.' '.$appellation->libelle] = $appellation->code;
                      foreach($appellation->mentions as $mention) {
                        foreach($mention->lieux as $lieu) {
                          foreach($lieu->couleurs as $couleur) {
                            $droits = (array)$couleur->droits->cvo;
                            if (!$droits) continue;
                            $droit = array_pop($droits);
                            $key = trim($certification->libelle.' '.$genre->libelle.' '.$appellation->libelle.' '.trim($mention->libelle.' '.$lieu->libelle.' '.$couleur->libelle));
                            $couleurs[$key] = str_replace([0,1,2,3,4,5,6,7,8,9], '', $droit->code);
                          }
                        }
                      }
                    }
                }
            }
        }
    }
    return (strpos($argv[1], 'CIVP') !== false)? $couleurs : $appellations;
}


$appellations = getAppellations();
global $appellations;
$factures = [];
$indEcheance = [];
while($line = trim(fgets(STDIN))) {
    if (!in_array(substr($line, 0, 3), ['VEN', 'ATT'])) continue;
    $tabLine = explode(';', $line);
    if (!isset($factures[$tabLine[3]])) $factures[$tabLine[3]] = initFactureTab($tabLine);
    if ($tabLine[14] == 'TVA') {
        $factures[$tabLine[3]]["TotalHTDocument"] = round($factures[$tabLine[3]]["TotalHTDocument"] - floatval($tabLine[10]), 2);
        continue;
    }
    if ($tabLine[14] == 'ECHEANCE') {
        if (!isset($indEcheance[$tabLine[3]])) {
            $indEcheance[$tabLine[3]] = 0;
            $factures[$tabLine[3]]["Echeances"][$indEcheance[$tabLine[3]]] = ["DateEcheance" => null, "LibelleModeReglement" => null, "MontantEcheance" => 0];
        }
        $factures[$tabLine[3]]["TotalHTDocument"] = round($factures[$tabLine[3]]["TotalHTDocument"] + floatval($tabLine[10]), 2);
        $factures[$tabLine[3]]["TotalTTCDocument"] = round($factures[$tabLine[3]]["TotalTTCDocument"] + floatval($tabLine[10]), 2);
        $factures[$tabLine[3]]["Echeances"][$indEcheance[$tabLine[3]]]["DateEcheance"] = date('d/m/Y', strtotime($tabLine[8]));
        $factures[$tabLine[3]]["Echeances"][$indEcheance[$tabLine[3]]]["LibelleModeReglement"] = (trim($tabLine[26]) == 'PRELEVEMENT')? 'Traite' : 'Cheque';
        $factures[$tabLine[3]]["Echeances"][$indEcheance[$tabLine[3]]]["MontantEcheance"] = round($factures[$tabLine[3]]["Echeances"][$indEcheance[$tabLine[3]]]["MontantEcheance"] + floatval($tabLine[10]), 2);
        $indEcheance[$tabLine[3]]++;
        $factures[$tabLine[3]]["NombreEcheance"]++;
        continue;
    }
    if ($tabLine[14] == 'LIGNE' && round(floatval($tabLine[10]), 2) != 0) {
        $factures[$tabLine[3]]["NombreLignesDocument"]++;
        $factures[$tabLine[3]]["DocumentLigne"][] = getDetail($tabLine);
    }
}

echo json_encode(array_values($factures), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)."\n";
