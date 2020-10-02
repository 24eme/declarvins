<?php

class importSIGTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Fichier HTML SIG à importer'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'import';
    $this->name             = 'sig';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $file = $arguments['file'];

    if (!is_file($file)) {
      echo "ERREUR;not a file;$file";
      exit;
    }
    $infos = pathinfo($file);

    if (!isset($infos['extension']) || !isset($infos['filename']) || strtolower($infos['extension']) != 'html') {
        echo "ERREUR;invalid filename (cvi.html);$file";
        exit;
    }
    $cvi = $infos['filename'];
    $etablissements = EtablissementIdentifiantView::getInstance()->findByIdentifiant($cvi)->rows;

    if (!count($etablissements)) {
          echo "ERREUR;CVI inconnu;$cvi";
          exit;
    }
    foreach($etablissements as $etablissement) {
      $fichier = FichierClient::getInstance()->createDoc(str_replace('ETABLISSEMENT-', '', $etablissement->id), true);
      $fichier->date_depot = '2020-10-01';
      $fichier->libelle = "Systèmes d’Informations Géographique";
      $fichier->save();
      try {
        $fichier->storeFichier($file);
      } catch (Exception $e) {
        echo $e;
        $fichier->remove();
        exit;
      }
      $fichier->save();
      echo "SUCCESS;$etablissement->id;$fichier->_id\n";
    }

  }
}
