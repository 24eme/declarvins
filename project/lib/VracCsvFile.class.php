<?php

class VracCsvFile extends CsvFile 
{
  const CSV_VRAC_DECLARANT_IDENTIFIANT = 0;
  const CSV_VRAC_DECLARANT_NOM = 1;
  const CSV_VRAC_ACHETEUR_IDENTIFIANT = 2;
  const CSV_VRAC_ACHETEUR_NOM = 3;
  const CSV_VRAC_CERTIFICATION = 4;
  const CSV_VRAC_APPELLATION = 5;
  const CSV_VRAC_LIEU = 6;
  const CSV_VRAC_COULEUR = 7;
  const CSV_VRAC_CEPAGE = 8;
  const CSV_VRAC_MILLESIME = 9;
  const CSV_VRAC_LABELS = 10;
  const CSV_VRAC_MENTION = 11;
  const CSV_VRAC_CONTRAT_DATE = 12;
  const CSV_VRAC_CONTRAT_NUMERO = 13;
  const CSV_VRAC_CONTRAT_VOLUME_PROMIS = 14;
  const CSV_VRAC_CONTRAT_VOLUME_REALISE = 15;

  private function verifyCsvLine($line) {
    if (!preg_match('/[^ ]+/', $line[self::CSV_VRAC_CONTRAT_NUMERO]))
      throw new Exception('Numero de contrat nécessaire : '.$line[self::CSV_VRAC_CONTRAT_NUMERO]);
    if (! $line[self::CSV_VRAC_CONTRAT_VOLUME_PROMIS]*1)
      throw new Exception('Volume promis nécessaire : '.$line[self::CSV_VRAC_CONTRAT_VOLUME_PROMIS]);
    $declarant = EtablissementClient::getInstance()->retrieveById($line[self::CSV_VRAC_DECLARANT_IDENTIFIANT]);
    if (!$declarant) {
      throw new Exception('Impossible de trouver un etablissement correspondant à l\'identifiant '.$line[self::CSV_VRAC_DECLARANT_IDENTIFIANT]);
    }
  }

  private function getProduit($line) {
    return $this->config->identifyNodeProduct($line[self::CSV_VRAC_CERTIFICATION], 
					  $line[self::CSV_VRAC_APPELLATION], 
					  $line[self::CSV_VRAC_LIEU], 
					  $line[self::CSV_VRAC_COULEUR], 
					  $line[self::CSV_VRAC_CEPAGE], 
					  $line[self::CSV_VRAC_MILLESIME]);
  }

  public function importContrats() {
    $this->config = ConfigurationClient::getCurrent();
    $this->errors = array();
    $this->numline = 0;
    $contrats = array();
    $csvs = $this->getCsv();
    try {
      foreach ($csvs as $line) {
	$this->verifyCsvLine($line);
	$hash = $this->getProduit($line);
	$c = VracClient::getInstance()->retrieveByNumeroAndEtablissementAndHashOrCreateIt($line[self::CSV_VRAC_CONTRAT_NUMERO], 
											     $line[self::CSV_VRAC_DECLARANT_IDENTIFIANT],
											     $hash);
	$c->add('acheteur')->add('nom', $line[self::CSV_VRAC_ACHETEUR_NOM]);
	$c->add('volume_promis', $line[self::CSV_VRAC_CONTRAT_VOLUME_PROMIS]*1);
	if (!$c->volume_realise)
	  $c->add('volume_realise', $line[self::CSV_VRAC_CONTRAT_VOLUME_REALISE]*1);
	$contrats[] = $c;
      }
    }catch(Execption $e) {
      $this->error[] = $e->getMessage();
    }
    return $contrats;
  }

  public function getErrors() {
    return $this->errors;
  }
}