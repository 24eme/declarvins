<?php
class contratCreateFromGRCFileTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('grcfile', sfCommandArgument::REQUIRED, 'GRC File'),
    	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));
        $this->namespace = 'contrat';
        $this->name = 'create-from-grcfile';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony contrat|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

      $lines = explode(PHP_EOL, file_get_contents($arguments['grcfile']));
      $num = ContratClient::getInstance()->getNextNoContrat();
      foreach ($lines as $line) {
          $datas = explode(';', $line);
          if (!$datas[0]) continue;

          $contrat = new Contrat();
          $contrat->_id = "CONTRAT-$num";
          $contrat->no_contrat = $num;
          $contrat->compte =  "COMPTE-$num";
          $contrat->generateByGrc($datas);
          $contrat->valide = 1;
          $contrat->save();

          $compte = new CompteTiers();
          $compte->_id =  "COMPTE-$num";
          $compte->generateByContrat($contrat);
          $compte->statut = _Compte::STATUT_FICTIF;
          $compte->valide = 0;
          $compte->contrat_valide = 1;
          $compte->save();

          $datas[EtablissementCsv::COL_NUMERO_CONTRAT] = $num;
          echo implode(';', $datas).PHP_EOL;
          $num++;
      }
    }
}
