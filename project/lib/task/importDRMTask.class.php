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

  private function CSV2DRM($csv) {
    if (!count($csv))
      return ;
    $csvDRM = DRMCsvFile::createFromArray($csv);
    try {
      $drm = $csvDRM->importDRM(array('no_droits'=>1,'no_vrac' => 1, 'init_line' => $this->line));
    }catch(Exception $e) {
      print_r($csvDRM->errors);
      throw new Exception("errors $e");
    }
    $this->line += count($csv);
    $drm->save();
    return;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $lignes = file($options['file']);
    $csv = array();
    $oldid = '';
    $this->line = 0;
    foreach ($lignes as $l) {
      $l = preg_replace('/"/', '', $l);
      if (preg_match('/^[^;]*;([^;]*)/', $l, $match)) {
	if (!preg_match('/[0-9]/', $match[1]))
	  continue;
	if ($match[1] != $oldid) {
	  $this->CSV2DRM($csv);
	  $csv = array();
	}
	$oldid = $match[1];
	$csv[] = split(';', $l);
      }
    }
    $this->CSV2DRM($csv);
  }
}
