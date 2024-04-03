<?php

class exportVolumeConditionneTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('mvtsDrmCsvFile', sfCommandArgument::REQUIRED, 'Fichier contenant les mvts DRM'),
      new sfCommandArgument('etablissementsCsvFile', sfCommandArgument::REQUIRED, 'Fichier contenant les etablissements'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('annee', null, sfCommandOption::PARAMETER_REQUIRED, 'Annee civile'),
      new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_REQUIRED, 'Interpro'),
    ));

    $this->namespace        = 'export';
    $this->name             = 'volume-conditionne';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $conf = ConfigurationClient::getConfiguration();

    $csvFile = $arguments['mvtsDrmCsvFile'];
    $etablissementCsvFile = $arguments['etablissementsCsvFile'];
    $annee = $options['annee'];
    $interpro = 'INTERPRO-'.str_replace('INTERPRO-', '', $options['interpro']);

    if (!file_exists($csvFile)) {
        echo "file doesn't exist";
        return;
    }

    $result = [];

    if (($handle = fopen($csvFile, "r")) !== false) {
      while (($data = fgetcsv($handle, null, ";")) !== false) {
          if (strpos($data[4], $annee) !== 0) {
            continue;
          }
          if (!in_array($data[30], ['sorties/crd', 'sorties/crd_acquittes'])) {
            continue;
          }
          if (!isset($result[$data[2]])) {
            $result[$data[2]] = 0;
          }
          $result[$data[2]] += $data[19];
      }
      fclose($handle);
    }

    $contrats = VracDateView::getInstance()->findByInterproAndDates($interpro, $annee.'-01-01', $annee.'-12-31')->rows;
    foreach ($contrats as $contrat) {
      if ($contrat->value[VracDateView::VALUE_TYPE_RETIRAISON] != 'tire_bouche') {
        continue;
      }
      $identifiant = $contrat->value[VracDateView::VALUE_VENDEUR_ID];
      if (!isset($result[$identifiant])) {
        $result[$identifiant] = 0;
      }
      $result[$identifiant] += $contrat->value[VracDateView::VALUE_VOLUME_PROPOSE];
    }

    if ($result) {
      echo '#ID DV;CVI;SIRET;ACCISES;RAISON SOCIALE;VOLUME CONDITIONNE '.$annee."\n";
      $etablissements = [];
      if (($handle = fopen($etablissementCsvFile, "r")) !== false) {
        while (($data = fgetcsv($handle, null, ";")) !== false) {
            $etablissements[$data[3]] = [$data[7], $data[20], $data[13], $data[9]];
        }
        fclose($handle);
      }
      foreach ($result as $id => $volume) {
        if (!isset($etablissements[$id])) {
          continue;
        }
        echo $id.';'.implode(';', $etablissements[$id]).';'.$volume."\n";
      }
    }
  }
}
