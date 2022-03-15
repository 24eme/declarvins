<?php

class generateSocieteByEtablissementTask extends sfBaseTask
{
  protected function configure()
  {
     $this->addArguments(array(
       new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, 'identifiant etablissement'),
     ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('code-comptable', null, sfCommandOption::PARAMETER_OPTIONAL, 'Numero de code comptable'),
      // add your own options here
    ));

    $this->namespace        = 'generate';
    $this->name             = 'societe-by-etablissement';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

  	$etablissement = EtablissementClient::getInstance()->find($arguments['identifiant']);
    $cc = null;
    if (isset($options['code-comptable']) && $options['code-comptable'] && !in_array($options['code-comptable'], ['4110000C0', '4110000C'])) {
      $cc = $options['code-comptable'];
    }

    if (!$etablissement) {
      $this->logSection("generate:societe-by-etablissement", "Etablissement not found with id : ".$arguments['identifiant'], null, 'ERROR');
      return;
    }

    if ($s = SocieteClient::getInstance()->find($etablissement->identifiant)) {
      if ($s->code_comptable_client) {
        $this->logSection("generate:societe-by-etablissement", "Société ".$arguments['identifiant']." ($cc) existante avec le code comptable : ".$s->code_comptable_client, null, 'WARNING');
        return;
      } else {
        $s->code_comptable_client = $cc;
        $s->save();
        $this->logSection("debug", "Affectation du code comptable $cc pour la societe déjà existante ".$s->_id, null, 'SUCCESS');
        return;
      }
    }

    try {
      $societe = $etablissement->getGenerateSociete();
    } catch (Exception $e) {
        $this->logSection("generate:societe-by-etablissement", "Generation impossible : ".$e->getMessage(), null, 'ERROR');
        return;
    }

    if ($cc) {
      $societe->code_comptable_client = $cc;
    }

    $societe->save();
    $this->logSection("debug", "Société créée avec succès ".$societe->_id." (".$societe->code_comptable_client.")", null, 'SUCCESS');

  }
}
