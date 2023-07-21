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
      new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_OPTIONAL, 'Interpro'),
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
    $interpro = null;
    if (isset($options['code-comptable']) && $options['code-comptable'] && !in_array($options['code-comptable'], ['4110000C0', '4110000C'])) {
      $cc = $options['code-comptable'];
    }
    if (isset($options['interpro']) && $options['interpro']) {
      $interpro = $options['interpro'];
    }

    if (!$etablissement) {
      $this->logSection("generate:societe-by-etablissement", "Etablissement not found with id : ".$arguments['identifiant'], null, 'ERROR');
      return;
    }

    if (($s = SocieteClient::getInstance()->find($etablissement->identifiant)) && $cc) {
      if ($ccExistant = $s->getCodeComptableClient($interpro)) {
        $this->logSection("generate:societe-by-etablissement", "Société ".$arguments['identifiant']." ($cc) existante avec le code comptable : ".$ccExistant, null, 'WARNING');
        return;
      } else {
        $s->addCodeComptableClient($cc, $interpro);
        $s->save();
        $this->logSection("generate:societe-by-etablissement", "Affectation du code comptable $cc pour la societe déjà existante ".$s->_id, null, 'SUCCESS');
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
      $societe->addCodeComptableClient($cc, $interpro);
    }

    $societe->save();
    $this->logSection("generate:societe-by-etablissement", "Société créée avec succès ".$societe->_id." (".$societe->getCodeComptableClient($interpro).")", null, 'SUCCESS');

  }
}
