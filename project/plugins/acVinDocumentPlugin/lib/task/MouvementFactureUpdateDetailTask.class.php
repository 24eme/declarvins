<?php

class mouvementFactureUpdateDetailTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'update';
    $this->name             = 'mouvement-facture-detail';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $context = sfContext::createInstance($this->configuration);

    $lines = file('php://stdin');

    foreach($lines as $line) {
      try {
        $id_doc = str_replace("\n", "", $line);

        try {
          $doc = acCouchdbManager::getClient()->find($id_doc);
        } catch(Exception $e) {

          throw new sfException(sprintf("WARNING; Le document '%s' n'a pas pu être récupéré\n", $id_doc));
        }
        if(!$doc instanceof InterfaceMouvementDocument) {

          throw new sfException(sprintf("WARNING;Le document '%s' n'est pas un document qui peut accueillir des mouvements\n", $id_doc));
        }

        /*if(!$doc instanceof InterfaceDroitDocument) {

          throw new sfException(sprintf("WARNING;Le document '%s' n'est pas un document qui peut accueillir des droits\n", $id_doc));
        }

        if(!$doc instanceof InterfaceValidableDocument) {

          throw new sfException(sprintf("WARNING;Le document '%s' n'est pas un document validable\n", $id_doc));
      }*/

        if(!$doc->isValidee()) {
          throw new sfException(sprintf("WARNING;Le document '%s' n'est pas validé\n", $id_doc));
        }

        if($doc->isNonFactures()) {
            throw new sfException(sprintf("WARNING;Le document '%s' n'est pas facturé, préférer la tache update\n", $id_doc));
        }

          $oldMvts = $doc->getOrAdd('mouvements')->getOrAdd($doc->identifiant);
          if (!$oldMvts) {
              return;
          }
          $newMvts =  array();
          $mvtsCalcules = $doc->getMouvementsCalcule();
          if ($mvtsCalcules && isset($mvtsCalcules[$doc->identifiant])) {
              foreach($mvtsCalcules[$doc->identifiant] as $mvt) {
                  $newMvts[] = $mvt;
              }
          }
          if (count($oldMvts) != count($newMvts)) {
               throw new sfException(sprintf("WARNING;Le document '%s' possède des mouvements inexistants\n", $id_doc));
          }
          $i = 0;
          foreach($oldMvts as $k => $v) {
              $newmvt = $newMvts[$i];
              $doc->get('mouvements')->get($doc->identifiant)->get($k)->add('denomination_complementaire', $newmvt->denomination_complementaire);
              $doc->get('mouvements')->get($doc->identifiant)->get($k)->add('produit_hash', $newmvt->produit_hash);
              $doc->get('mouvements')->get($doc->identifiant)->get($k)->add('produit_libelle', trim($newmvt->produit_libelle));
              $i++;
          }
          $doc->save();

        echo $doc->_id."\n";

      } catch(Exception $e) {
        fprintf(STDERR, "%s", $e->getMessage());
      }
    }

  }
}
