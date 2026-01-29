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
      new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_REQUIRED, 'interpro'),
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
    $interpro = 'INTERPRO-'.str_replace('INTERPRO-', '', strtoupper($options['interpro']));

    if (!$interpro) {
        echo "interpro required\n";
        return;
    }

    if (!file_exists($csvFile)) {
        echo "file doesn't exist\n";
        return;
    }

    $items = array_map(function($line) { return str_getcsv($line, ";"); }, file($csvFile));
    foreach($items as $datas) {
        $cvi = str_pad(trim($datas[0]), 10, "0", STR_PAD_LEFT);
        $identifiant = $datas[1];
        $hash = $datas[2];
        $millesime = $datas[3];
        $volume = (trim($datas[4]))? round(floatval(str_replace(',', '.', trim($datas[4]))), 5) : null;
        $capaciteCom = (isset($datas[5]))? round(floatval(str_replace(',', '.', trim($datas[5]))), 5) : null;

        if (!$volume) {
            $volume = 0;
        }

        $etablissement = ($cvi)? $conf->identifyEtablissement($cvi) : null;
        if (!$etablissement) {
            if (strpos($identifiant, 'CIVP') === false && strpos($identifiant, '-') === false) {
                $ids = DRMReserveInterproView::getInstance()->identifyEtablissement("INTERPRO-IR", $identifiant);
                if (count($ids) > 1) {
                    echo "etablissement unidentifiable $identifiant ($cvi)\n";
                    continue;
                }
                if (!$ids) {
                    $identifiant .= '-01';
                } else {
                    $identifiant = $ids[0];
                }
            }
            $etablissement = EtablissementClient::getInstance()->find($identifiant);
        }
        if (!$etablissement) {
            echo "etablissement not found $identifiant ($cvi)\n";
            continue;
        }

        $historique = new DRMHistorique($etablissement->identifiant);
        $lastDRM = $historique->getLastDRM();
        if (!$lastDRM) {
            echo "pas de drm pour l'etablissement $etablissement->identifiant ($cvi)\n";
            continue;
        }
        $drms = [$lastDRM];
        if (!$lastDRM->isValidee()) {
            $drms = [$historique->getPreviousDRM($lastDRM->getPeriode()), $lastDRM];
        }
        foreach ($drms as $drm) {
            if ($drm->exist($hash)) {
                $produit = $drm->get($hash);
                $produit->setReserveInterpro($volume, $millesime);
                if ($capaciteCom) {
                    $produit->setCapaciteCommercialisation($capaciteCom, $millesime);
                }
                $drm->updateAutoReserveInterpro();
                if (!$checkingMode) $drm->save();
                echo $drm->_id." add reserve $millesime : $volume hl for $hash\n";
            } else {
                echo $drm->_id." produit manquant $hash\n";
            }
        }
    }
  }
}
