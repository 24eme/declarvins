<?php

class importOIOCTask extends sfBaseTask
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
    $this->name             = 'oioc';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importInterpo|INFO] task does things.
Call it with:

  [php symfony importOIOC|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $oiocs = OIOCAllView::getInstance()->findAll();
    foreach ($oiocs->rows as $o) {
    	$oioc = OIOCClient::getInstance()->find($o->key[OIOCAllView::KEY_ID]);
    	$oioc->delete();
    }
    
    $oioc = new OIOC();
    $oioc->set('_id', 'OIOC-OIVR');
    $oioc->identifiant = 'OIVR';
    $oioc->nom = 'OIVR';
    $oioc->save();
    $this->logSection('oioc', 'OIVR importé');
    
    $oioc = new OIOC();
    $oioc->set('_id', 'OIOC-Vinomed');
    $oioc->identifiant = 'Vinomed';
    $oioc->nom = 'Vinomed';
    $oioc->save();
    $this->logSection('oioc', 'Vinomed importé');
    
    $oioc = new OIOC();
    $oioc->set('_id', 'OIOC-AVPI');
    $oioc->identifiant = 'AVPI';
    $oioc->nom = 'AVPI';
    $oioc->save();
    $this->logSection('oioc', 'AVPI importé');
    
    $oioc = new OIOC();
    $oioc->set('_id', 'OIOC-OTC');
    $oioc->identifiant = 'OTC';
    $oioc->nom = 'OTC';
    $oioc->save();
    $this->logSection('oioc', 'OTC importé');
  }
}
