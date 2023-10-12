<?php
class contratValidateFromGRCFileTask extends sfBaseTask {

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
        $this->name = 'validate-from-grcfile';
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

      $contextInstance = sfContext::createInstance($this->configuration);

      $lines = explode(PHP_EOL, file_get_contents($arguments['grcfile']));
      foreach ($lines as $line) {
          $datas = explode(';', $line);
          if (!isset($datas[EtablissementCsv::COL_NUMERO_CONTRAT]) || !$datas[EtablissementCsv::COL_NUMERO_CONTRAT]) continue;
          if ($contrat = ContratClient::getInstance()->retrieveById($datas[EtablissementCsv::COL_NUMERO_CONTRAT])) {
              $compte = $contrat->getCompteObject();
              $compte->interpro->getOrAdd($datas[EtablissementCsv::COL_INTERPRO])->setStatut(_Compte::STATUT_VALIDE);
              $compte->setStatut(_Compte::STATUT_ATTENTE);
              $compte->save();
              Email::getInstance()->sendCompteRegistrationAutomatique($compte, $compte->email);
              echo $datas[EtablissementCsv::COL_NUMERO_CONTRAT]." validé\n";
          } else {
              echo "Pas de contrat numéro ".$datas[EtablissementCsv::COL_NUMERO_CONTRAT]."\n";
          }

      }
    }
}
