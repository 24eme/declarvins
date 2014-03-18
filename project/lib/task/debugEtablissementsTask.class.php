<?php

class debugEtablissementsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'debug';
    $this->name             = 'etablissements';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
  		$rows = acCouchdbManager::getClient()
              ->getView("etablissement", "all")
              ->rows;
      $i = 0;
      foreach($rows as $row) {
      	$etab = EtablissementClient::getInstance()->find($row->key[4]);
      	if ($etab) {
      		$rs = $etab->raison_sociale;
      		$nom = $etab->nom;
      		$etab->raison_sociale = $nom;
      		$etab->nom = $rs;
	        $etab->save();
	        $this->logSection("debug", $etab->get('_id'), null, 'SUCCESS');
	        $i++;
      	}
      }
      $this->logSection("debug", $i." etablissement(s) debuggué(s) avec succès", null, 'SUCCESS');
    
  }
}
