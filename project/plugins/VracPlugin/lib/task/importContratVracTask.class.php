<?php

class importContratVracTask extends sfBaseTask
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
    $this->name             = 'vrac';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importContratVrac|INFO] task does things.
Call it with:

  [php symfony importContratVrac|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $vrac = acCouchdbManager::getClient()->find('VRAC-123456');
    if($vrac) {
      $vrac->delete();
    }

    $vrac = new Vrac();
    $vrac->_id = 'VRAC-123456';
    $vrac->produit = '/declaration/certifications/AOP';
    $vrac->numero = '123456';
    $vrac->acheteur->nom = "Acheteur test";
    $vrac->etablissement = '9223700100';
    $vrac->volume_promis = 25;
    $vrac->prix = 500;
    $vrac->courtier->nom = "Courtier test";
    $vrac->actif = 1;
    $vrac->date_creation = "2012-04-27";
    $vrac->save();

    $vrac = acCouchdbManager::getClient()->find('VRAC-123457');
    if($vrac) {
      $vrac->delete();
    }
    
    $vrac = new Vrac();
    $vrac->_id = 'VRAC-123457';
    $vrac->produit = '/declaration/certifications/IGP/appellations';
    $vrac->numero = '123457';
    $vrac->acheteur->nom = "Acheteur test";
    $vrac->etablissement = '9223700100';
    $vrac->volume_promis = 36;
    $vrac->prix = 500;
    $vrac->courtier->nom = "Courtier test";
    $vrac->actif = 1;
    $vrac->date_creation = "2011-04-27";
    $vrac->save();

  }
}
