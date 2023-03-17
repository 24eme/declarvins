<?php

class importReserveInterproTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('csvFile', sfCommandArgument::REQUIRED, 'Fichier contenant les réserves à importer'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
    ));

    $this->namespace        = 'import';
    $this->name             = 'reserve-interpro';
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

    $csvFile = $arguments['csvFile'];
    $checkingMode = $options['checking'];

    if (!file_exists($csvFile)) {
        echo "file doesn't exist";
        return;
    }

    $items = array_map(function($line) { return str_getcsv($line, ";"); }, file($csvFile));
    foreach($items as $datas) {
        $cvi = str_pad(trim($datas[0]), 10, "0", STR_PAD_LEFT);
        $identifiant = $datas[1];
        $hash = $datas[2];
        $volume = str_replace(',', '.', $datas[3])*1;
        $etablissement = $conf->identifyEtablissement($cvi);
        if (!$etablissement) {
            if (strpos($identifiant, '-') === false) {
                $identifiant .= '-01';
            }
            $etablissement = EtablissementClient::getInstance()->find($identifiant);
        }
        if (!$etablissement) {
            echo "etablissement not found $identifiant ($cvi)\n";
            continue;
        }

        $historique = new DRMHistorique($etablissement->identifiant);
        $lastDRM = $historique->getLastDRM();
        $prevDRM = null;
        if (!$lastDRM->isValidee()) {
            $prevDRM = $historique->getPreviousDRM($lastDRM->getPeriode());
            if ($prevDRM->exist($hash)) {
                $produit = $prevDRM->get($hash);
                $produit->add('reserve_interpro', $volume);
                $prevDRM->save();
                if (!$checkingMode) echo $prevDRM->_id." add reserve $volume hl\n";
            } else {
                echo $prevDRM->_id." produit manquant $hash\n";
            }
        }
        if ($lastDRM->exist($hash)) {
            $produit = $lastDRM->get($hash);
            $produit->add('reserve_interpro', $volume);
            if (!$checkingMode) $lastDRM->save();
            echo $lastDRM->_id." add reserve $volume hl\n";
        } else {
            echo $lastDRM->_id." produit manquant $hash\n";
        }
    }

  }
}
