<?php

class VracCsvFile extends CsvFile 
{
  const CSV_VRAC_DECLARANT_IDENTIFIANT = 0;
  const CSV_VRAC_DECLARANT_NOM = 1;
  const CSV_VRAC_CONTRAT_NUMERO = 2;
  const CSV_VRAC_CERTIFICATION = 3;
  const CSV_VRAC_CERTIFICATION_CODE = 4; 
  const CSV_VRAC_GENRE = 5;
  const CSV_VRAC_GENRE_CODE = 6;
  const CSV_VRAC_APPELLATION = 7;
  const CSV_VRAC_APPELLATION_CODE = 8;
  const CSV_VRAC_MENTION = 9;
  const CSV_VRAC_MENTION_CODE = 10;
  const CSV_VRAC_LIEU = 11;
  const CSV_VRAC_LIEU_CODE = 12;
  const CSV_VRAC_COULEUR = 13;
  const CSV_VRAC_COULEUR_CODE = 14;
  const CSV_VRAC_CEPAGE = 15;
  const CSV_VRAC_CEPAGE_CODE = 16;
  const CSV_VRAC_MILLESIME = 17;
  const CSV_VRAC_MILLESIME_CODE = 18;
  const CSV_VRAC_LABELS = 19;
  const CSV_VRAC_LABELS_CODE = 20;
  const CSV_VRAC_MENTION_EXTRA = 21;
  const CSV_VRAC_MENTION_EXTRA_CODE = 22;
  const CSV_VRAC_ACHETEUR_IDENTIFIANT = 23;
  const CSV_VRAC_ACHETEUR_NOM = 24;
  const CSV_VRAC_COURTIER_IDENTIFIANT = 25;
  const CSV_VRAC_COURTIER_NOM = 26;
  const CSV_VRAC_CONTRAT_DATE = 27;
  const CSV_VRAC_CONTRAT_VOLUME_PROMIS = 28;
  const CSV_VRAC_CONTRAT_VOLUME_REALISE = 29;
  const CSV_VRAC_CONTRAT_VOLUME_PRIX = 30;

  private function verifyCsvLine($line) {
    if (!preg_match('/[0-9]/', $line[self::CSV_VRAC_CONTRAT_NUMERO]))
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
					  $line[self::CSV_VRAC_GENRE], 
					  $line[self::CSV_VRAC_APPELLATION], 
					  $line[self::CSV_VRAC_MENTION], 
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
    $ligne = 0;
    foreach ($csvs as $line) {
      $ligne++;
      array_splice($line, self::CSV_VRAC_MENTION, 0, array('', ''));
      if (!$line[0])
	continue;
      try {
	$this->verifyCsvLine($line);
	$hash = $this->getProduit($line);
	$c = VracClient::getInstance()->retrieveByNumeroAndEtablissementAndHashOrCreateIt($line[self::CSV_VRAC_CONTRAT_NUMERO], 
											  $line[self::CSV_VRAC_DECLARANT_IDENTIFIANT],
											  $hash);
	$c->vendeur->nom = $line[self::CSV_VRAC_ACHETEUR_NOM];
	$c->acheteur_identifiant = $line[self::CSV_VRAC_DECLARANT_IDENTIFIANT];
	$c->acheteur->nom = $line[self::CSV_VRAC_DECLARANT_NOM];
	$c->add('acheteur')->add('nom', $line[self::CSV_VRAC_ACHETEUR_NOM]);
	$c->add('volume_propose', $line[self::CSV_VRAC_CONTRAT_VOLUME_PROMIS]*1);
	if (!$c->volume_enleve)
	  $c->add('volume_enleve', $line[self::CSV_VRAC_CONTRAT_VOLUME_REALISE]*1);
	if ($c->volume_enleve < $c->volume_propose) {
	  $c->valide->statut = "NONSOLDE";
	}else{
	  $c->valide->statut = "SOLDE";
	}
	$contrats[] = $c;
      }catch(Exception $e) {
	$this->errors[] = array('ligne' => $ligne, 'message' => $e->getMessage());
      }
    }
    return $contrats;
  }

  public function getErrors() {
    return $this->errors;
  }
}
