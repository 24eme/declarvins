<?php

class PaiementsGenerateRemisesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
			    new sfCommandArgument('paiementsCsv', null, sfCommandOption::PARAMETER_REQUIRED, 'Csv des paiements'),
    ));

    $this->addOptions(array(
			    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
			    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
			    new sfCommandOption('filename', null, sfCommandOption::PARAMETER_REQUIRED, 'Nom du fichier'),

      // add your own options here
    ));

    $this->namespace        = 'paiements';
    $this->name             = 'generate-remises';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [generatePDF|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $contextInstance = sfContext::createInstance($this->configuration);

    $remises = array();
    foreach (file($arguments['paiementsCsv']) as $ligne) {
        if (strpos($ligne, ';DEBIT;') === false) {
            continue;
        }
        $datas = explode(';', $ligne);
        if (!isset($remises[$datas[16]])) {
            $remises[$datas[16]] = array();
        }
        $remises[$datas[16]][] = $datas;
    }

	$pdf = new ExportRemisesPdf($remises);
	$filename = $pdf->generate();
    rename($filename, $options['filename']);

  }
}
