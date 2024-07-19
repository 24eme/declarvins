<?php

class importSV12Task extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('csvFile', sfCommandArgument::REQUIRED, 'Fichier contenant les SV12 à importer'),
      new sfCommandArgument('interpro', sfCommandArgument::REQUIRED, 'interpro cible'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
      new sfCommandOption('campagne', null, sfCommandOption::PARAMETER_OPTIONAL, 'Campagne'),
      new sfCommandOption('mvtsalwaysfactures', null, sfCommandOption::PARAMETER_OPTIONAL, 'Mvts'),
    ));

    $this->namespace        = 'import';
    $this->name             = 'SV12';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csvFile = $arguments['csvFile'];
    $interpro = str_replace('INTERPRO-', '', $arguments['interpro']);
    $checkingMode = $options['checking'];
    $campagneOpt = $options['campagne'];
    $mvtsalwaysfacturesOpt = $options['mvtsalwaysfactures'];

    if (!file_exists($csvFile)) {
        echo "file doesn't exist";
        return;
    }

    $items = array_map(function($line) { return str_getcsv($line, ";"); }, file($csvFile));
    $conf = ConfigurationClient::getConfiguration();

    $result = array();
    $documents = array();
    foreach($items as $datas) {
        $campagne = $datas[1];
        $cvi = str_pad(trim($datas[3]), 10, "0", STR_PAD_LEFT);
        $idProduit = trim($datas[16]);
        if (!in_array($datas[19], ['15VF', '15M'])) {
            continue;
        }
        if ($campagneOpt && $campagneOpt != $campagne) {
            continue;
        }
        $volume = str_replace(',', '.', $datas[21])*1;
        $produit = ($this->isHashProduit($idProduit))? $conf->identifyProduct($idProduit) : $conf->identifyProduct(null, "($idProduit)");
        if (!$produit && !$this->isHashProduit($idProduit) && substr($idProduit, -3, 1) == ' ' && strlen($idProduit) == 8) {
            $produit = $conf->identifyProduct(null, "(".substr($idProduit, 0, 5).' 00'.")");
        }
        if (!$produit) {
            echo "product not found $idProduit : $datas[17]\n";
            continue;
        }
        if ($produit->getDocument()->interpro != "INTERPRO-$interpro") {
            continue;
        }
        $labels = $this->getLabels($datas[35]);
        $etablissements = EtablissementIdentifiantView::getInstance()->findByIdentifiant($cvi)->rows;
        $items = [];
        foreach($etablissements as $e) {
            $etab = EtablissementClient::getInstance()->find($e->id);
            if ($etab->statut == Etablissement::STATUT_ARCHIVE) continue;
            if ($etab->sous_famille !=  EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR) continue;
            $items[] = $etab;
        }
        $hasMany = false;
        if (count($items) > 1) {
            $hasMany = true;
            $del = [];
            foreach ($items as $ind => $item) {
                $annees = explode('-', $campagne);
                if (!SV12Client::getInstance()->findMaster($item->identifiant, ($annees[0] - 1) . '-' . ($annees[1] - 1))) {
                    $del[] = $ind;
                }
            }
            foreach ($del as $i) {
                unset($items[$i]);
            }
        }
        if (!$hasMany && !count($items)) {
          echo "etablissement cvi not found $cvi : $datas[4]\n";
          continue;
        }
        if (($hasMany && !count($items))||(count($items) > 1)) {
          echo "Plusieurs etablissements pour le cvi : $cvi\n";
          continue;
        }
        $etablissement = current($items);
        $key = $campagne.'-'.$cvi;
        $identifiant = SV12Client::SV12_KEY_SANSVITI.'-'.SV12Client::SV12_TYPEKEY_VENDANGE.str_replace('/', '-', $produit->getHash());
        $identifiant .= ($labels && $labels != ['conv'])? '-'.implode('_', $labels) : '';
        if (!isset($result[$key])) {
            $sv12 = SV12Client::getInstance()->findMaster($etablissement->identifiant, $campagne);
            if (!$sv12) {
                $sv12 = SV12Client::getInstance()->createOrFind($etablissement->identifiant, $campagne);
            }
            if ($sv12->isValidee()) {
                $sv12 = $sv12->generateModificative();
                if ($interpro == 'IVSE') {
                    $sv12->cleanContratsProduit('/IGP/');
                } else {
                    $sv12->cleanContratsProduit('/AOP/');
                }

            }
            if ($sv12->isNew()) {
                $sv12->constructId();
            }
            $sv12->storeContrats();
        } else {
            $sv12 = $result[$key];
        }
        $exist = $sv12->contrats->exist($identifiant);
        $sv12Contrat = $sv12->contrats->getOradd($identifiant);
        if (!$exist) {
            $sv12Contrat->updateNoContrat($produit, array('contrat_type' => SV12Client::SV12_TYPEKEY_VENDANGE, 'volume' => $volume));
        } else {
            $sv12Contrat->volume = round($sv12Contrat->volume + $volume, 2);
        }
        $sv12Contrat->produit_libelle = $this->getProduitLibelleWithLabel($produit->getLibelleFormat(null, "%format_libelle% %la%"), $labels);
        $sv12Contrat->add('labels', $labels);
        $result[$key] = $sv12;

    }
    foreach($result as $sv12) {
        $avoir = null;
        if ($sv12->hasVersion()) {
            $previous = $sv12->getMother();
            $same = true;
            foreach ($sv12->contrats as $key => $contrat) {
                if (!$previous->contrats->exist($key)) {
                    $same = false;
                    break;
                }
                if ($previous->contrats->get($key)->volume != $contrat->volume) {
                    $same = false;
                    break;
                }
            }
            if ($same) {
                continue;
            }
            $avoir = $previous->getMvtsFactures("INTERPRO-$interpro");
        }
        $sv12->validate();
        if ($avoir) {
            $sv12->avoiriserMvts($avoir);
        }
        foreach($sv12->mouvements as $mouvements) {
            foreach($mouvements as $mouvement) {
                $mouvement->add('interpro', "INTERPRO-$interpro");
                if ($mvtsalwaysfacturesOpt && $mouvement->facturable) {
                    $mouvement->facture = 1;
                }
                if ($avoir && !$sv12->hasVolumeAFacturer()) {
                    $mouvement->facturable = 0;
                    $mouvement->facture = 0;
                }
            }
        }
        if (!$checkingMode) {
            $sv12->save();
        }
        echo "SV12 created $sv12->_id\n";
    }

  }
  public function getLabels($str) {
      $correspondances = ['BIO' => 'biol', 'DEMETER' => 'biod', 'CONVERSION_BIO' => 'bioc', 'HVE' => 'hve'];
      $labels = [];
      foreach($correspondances as $label => $correspondance) {
          if (strpos($str, $label) !== false) $labels[] = $correspondance;
      }
      if (!$labels) {
          $labels[] = 'conv';
      }
      return $labels;
  }

  public function isHashProduit($str) {
      $composants = ['certifications', 'genres', 'appellations', 'mentions', 'lieux', 'couleurs', 'cepages'];
      foreach($composants as $composant) {
          if (strpos($str, $composant) === false) {
              return false;
          }
      }
      return true;
  }

  public function getProduitLibelleWithLabel($produit_libelle, $labels) {
    $labelsLibelles = ['biol' => 'Bio', 'biod' => 'Biodynamie', 'bioc' => 'Bio en conversion', 'hve' => 'HVE 3'];
    $libelles = [];
    foreach($labels as $label) {
        if (isset($labelsLibelles[$label])) $libelles[] = $labelsLibelles[$label];
    }
    return trim(trim($produit_libelle).' '.implode(', ', $libelles));
  }
}
