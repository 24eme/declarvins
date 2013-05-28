<?php

class importDRMTask extends sfBaseTask
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
      new sfCommandOption('file', null, sfCommandOption::PARAMETER_REQUIRED, 'DRM History File')
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'DRM';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  private function CSV2DRM($csv) 
  {
    if (!count($csv))
      return ;
    $csvDRM = DRMCsvFile::createFromArray($csv);
    try {
      $drm = $csvDRM->importDRM();
    }catch(Exception $e) {
    	$errors = $csvDRM->getErrors();
      if (count($errors) > 0) {
		echo "ERROR: [ligne ".$errors[0]['line']."] ".$errors[0]['message']."\n";
      }else{
		echo "ERROR: $e\n";
      }
    }
    return;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $lignes = file($options['file']);
    $csv = array();
    foreach ($lignes as $ligne) {
    	$ligne = preg_replace('/"/', '', $ligne);
    	$csv[] = explode(';', $ligne);
    }
    $this->CSV2DRM($csv);
  }
}
