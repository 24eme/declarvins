<?php

class importConfigurationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'configuration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importConfiguration|INFO] task does things.
Call it with:

  [php symfony importConfiguration|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $import_dir = sfConfig::get('sf_data_dir').'/import/configuration';

    if ($current = acCouchdbManager::getClient()->retrieveDocumentById('CURRENT')) {
        $current->delete();
    }
    
    $current = new Current();
    $current->campagne = '2011-11';
    $current->save();
    $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION', acCouchdbClient::HYDRATE_JSON);
    if ($configuration) {
      acCouchdbManager::getClient()->deleteDoc($configuration);
    }
	    
    $configuration = new Configuration();

    $certifications = array();
    foreach (file($import_dir.'/certifications') as $line) {
      $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));
      $certifications[$datas[0]] = $datas[1];
    }

    $appellations = array();
    foreach (file($import_dir.'/appellations') as $line) {
      $datas = explode(';', preg_replace('/"/', '', str_replace("\n", "", $line)));
      $appellations[$datas[0]][$datas[1]] = $datas[2];
    }
    
    $couleurs = array();
    foreach (file($import_dir.'/couleurs') as $line) {
      $datas = explode(';', preg_replace('/"/', '', str_replace("\n", "", $line)));
      $couleurs[$datas[0]] = $datas[1];
    }

    foreach (file($import_dir.'/produits') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));

        foreach($datas as $key => $value) {
          if (!$value) {
            $datas[$key] = "DEFAUT";
          }
        }

        $hash = 'certifications/'.$datas[0].
                '/appellations/'.$datas[1].
                '/lieux/'.$datas[2].
                '/couleurs/'.$datas[3].
                '/cepages/'.$datas[4].
                '/millesimes/'.$datas[5];

        $configuration->declaration->getOrAdd($hash);
    }

    foreach($configuration->declaration->certifications as $certification) {
      if(array_key_exists($certification->getKey(), $certifications)) {
        $certification->libelle = $certifications[$certification->getKey()];
      } else {
        throw new sfCommandException("Libelle not found");
      }
      foreach($certification->appellations as $appellation) {
        if(array_key_exists($certification->getKey(), $appellations) && array_key_exists($appellation->getKey(), $appellations[$certification->getKey()])) {
          $appellation->libelle = $appellations[$certification->getKey()][$appellation->getKey()];
        } elseif($appellation->getKey() != "DEFAUT") {
          throw new sfCommandException(sprintf("Libelle not found : %s", $appellation->getHash()));
        }
        foreach($appellation->lieux->get('DEFAUT')->couleurs as $couleur) {
          if(array_key_exists($couleur->getKey(), $couleurs)) {
            $couleur->libelle = $couleurs[$couleur->getKey()];
          } elseif($couleur->getKey() != "DEFAUT") {
            throw new sfCommandException("Libelle not found");
          }
        }
      }
    }

    foreach (file($import_dir.'/appellations_interpros') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));

        foreach($datas as $key => $value) {
          if (!$value) {
            $datas[$key] = "DEFAUT";
          }
        }

        $hash = 'certifications/'.$datas[0].
                '/appellations/'.$datas[1];

        $configuration->declaration->get($hash)->interpro->add(null, 'INTERPRO-'.$datas[2]);
    }

    foreach (file($import_dir.'/appellations_departements') as $line) {
        $datas = explode(";", preg_replace('/"/', '', str_replace("\n", "", $line)));

        foreach($datas as $key => $value) {
          if (!$value) {
            $datas[$key] = "DEFAUT";
          }
        }

        $hash = 'certifications/'.$datas[0].
                '/appellations/'.$datas[1];

        $configuration->declaration->get($hash)->departements->add(null, $datas[2]);
    }

  	$configuration->label->add('AB', 'Agriculture Biologique');
  	$configuration->label->add('AR', 'Agriculture Raisonnée');
  	$configuration->label->add('BD', 'Biodynamie');
  	$configuration->label->add('AC', 'Agri confiance');
  	$configuration->label->add('TV', 'Terra Vitis');
  	$configuration->label->add('DD', 'Vigneron développement durable');
  	$configuration->label->add('NMP', 'Nutrition Méditérannéenne en Provence');
  	$configuration->label->add('HVE', 'Haute Valeur Environnementale');
  	$configuration->label->add('FU', 'Elevage en fût');
  	$configuration->label->add('DO', 'Domaine');
  	$configuration->label->add('CH', 'Château');
  	$configuration->label->add('CL', 'Clos');
  	$configuration->label->add('CC', 'Cru Classé');
  	$configuration->label->add('BT', 'Mise en bouteille ("conditionné") à la propriété');

  	$configuration->save();
  }
}