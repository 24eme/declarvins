<?php

class importDouaneTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'douane';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importDouane|INFO] task does things.
Call it with:

  [php symfony importDouane|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $douanes = acCouchdbManager::getClient("Douane")->getAll();
    foreach($douanes as $douane) {
        $douane->delete();
    }
    
    $douanes = array(
        "Aix-en-Provence",
        "Brignoles",
        "Draguignan",
        "Châteauneuf du Pape",
        "Cairanne",
        "Portes les Valence",
        "Tain l'Hermitage",
        "Bagnols sur Ceze",
        "Nîmes",
        "Privas",
        "Orange",
        "Sablet",
        "Saint Egreve");
    
    foreach($douanes as $nom) {
        $douane = new Douane();
        $identifiant = strtoupper(str_replace(array(" ","â", "-", "î", "'") ,array("", "a", "", "i", "") ,$nom));
        $douane->set('_id', $dounae->generateId($identifiant));
        $douane->nom = $nom;
        $douane->identifiant = $identifiant;
        $douane->save();
    	$this->logSection('douane', $nom.' importé');
    }

    // add your code here
  }
}
