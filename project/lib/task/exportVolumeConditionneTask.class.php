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
          if (!in_array($data[30], ['sorties/export', 'sorties/factures', 'sorties/crd', 'sorties/crd_acquittes'])) {
            continue;
          }
          if (!isset($result[$data[2]])) {
            $result[$data[2]] = 0;
          }
          $result[$data[2]] += $data[19];
      }
      fclose($handle);
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
