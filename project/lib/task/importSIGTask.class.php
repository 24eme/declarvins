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
      echo "ERREUR;not a file;$file\n";
      exit;
    }
    $infos = pathinfo($file);

    if (!isset($infos['extension']) || !isset($infos['filename']) || strtolower($infos['extension']) != 'html') {
        echo "ERREUR;invalid filename (cvi.html);$file\n";
        exit;
    }
    $cvi = $infos['filename'];
    $etablissements = EtablissementIdentifiantView::getInstance()->findByIdentifiant($cvi)->rows;

    if (!count($etablissements)) {
          echo "ERREUR;CVI inconnu;$cvi\n";
          exit;
    }
    foreach($etablissements as $etablissement) {
      $identifiant = str_replace('ETABLISSEMENT-', '', $etablissement->id);
      $historique = PieceAllView::getInstance()->getPiecesByEtablissement($identifiant, true);
      foreach($historique as $fich) {
          if (preg_match('/^FICHIER-/', $fich->id)) {
              $fichier = FichierClient::getInstance()->find($fich->id);
              $fichier->delete();
          }
      }
      $fichier = FichierClient::getInstance()->createDoc($identifiant, true);
      $fichier->date_depot = '2020-10-01';
      $fichier->libelle = "Systèmes d’Informations Géographique";
      $fichier->save();
      try {
        $fichier->storeFichier($file);
      } catch (Exception $e) {
        echo $e."\n";
        $fichier->delete();
        exit;
      }
      $fichier->save();
      echo "SUCCESS;$etablissement->id;$fichier->_id\n";
    }

  }
}
