<?php

class updateCodesComptableSocietesTask extends sfBaseTask {

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('extravitis', sfCommandArgument::REQUIRED, "Fichier csv extravitis pour l'update"),
            new sfCommandArgument('declarvins', sfCommandArgument::REQUIRED, "Fichier csv declarvins pour l'update"),
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'update';
        $this->name = 'codes-comptable';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $extravitisCsv = $arguments['extravitis'];
        $declarvinsCsv = $arguments['declarvins'];

        $correspondancesDv = $this->getCorrespondances($declarvinsCsv);

        $row = 1;
        if (($handle = fopen($extravitisCsv, "r")) !== false) {
            while (($data = fgetcsv($handle, 0, ";")) !== false) {

              $id = substr($data[0], 1)*1;
              $cvi = $data[18];
              $siret = $data[21];
              $numEV = $data[5];
              $etablissement = [];

              // CORRESPONDANCES + (CVI || SIRET)
              if (isset($correspondancesDv[$id])) {
                foreach($correspondancesDv[$id] as $etabId) {
                  $e = EtablissementClient::getInstance()->find($etabId);
                  if (!$cvi && !$siret) {
                    $etablissement[] = $e;
                    continue;
                  }
                  if (($cvi && $e->cvi == $cvi)||($siret && $e->siret == $siret)) {
                    $etablissement[] = $e;
                  }
                }
              }
              if (!$etablissement && $cvi && $siret) {
                // CVI + SIRET
                $etablissement = $this->identifyIdentifiant($cvi, $siret, 'siret');
                // SIRET + CVI
                if (!$etablissement) {
                  $etablissement = $this->identifyIdentifiant($siret, $cvi, 'cvi');
                }
              }
              // CVI
              if (!$etablissement && $cvi) {
                $etablissement = $this->identifyIdentifiant($cvi);
              }
              // SIRET
              if (!$etablissement && $siret) {
                $etablissement = $this->identifyIdentifiant($siret);
              }
              // CORRESPONDANCE
              if (!$etablissement && isset($correspondancesDv[$id])) {
                foreach($correspondancesDv[$id] as $etabId) {
                  $etablissement[] = EtablissementClient::getInstance()->find($etabId);
                }
              }

              $data[] = count($etablissement);
              $data[] = $this->getIds($etablissement);
              $data[] = "4110000C$numEV";
              echo str_replace('|', ';', str_replace(['"', ';'], '', implode($data, "|")))."\n";
            }
            fclose($handle);
        }
    }
    public function getIds($etablissements) {
      $ids = [];
      foreach ($etablissements as $etablissement) {
        $ids[] = $etablissement->identifiant;
      }
      return ($ids)? implode(',', $ids) : '';
    }
    public function getCorrespondances($declarvinsCsv) {
      $result = array();
      if (($handle = fopen($declarvinsCsv, "r")) !== false) {
          while (($data = fgetcsv($handle, 0, ";")) !== false) {
            if ($correspondance = $this->getIvseId($data[36])) {
              $result[$correspondance][] = $data[0];
            }
          }
      }
      return $result;
    }

    public function getIvseId($str) {
      if (preg_match('/IVSE:([A-Z0-9]+)/', $str, $m)) {
        return str_replace('IVSE', '', $m[1])*1;
      }
      return null;
    }

    public function identifyIdentifiant($id, $idCorrespondance = null, $idCorrespondanceLibelle = null) {
      $item = EtablissementIdentifiantView::getInstance()->findByIdentifiant($id)->rows;
      if ($item) {
        if (count($item) == 1) {
          $etablissement = EtablissementClient::getInstance()->find($item[0]->id);
          if ($idCorrespondance && $etablissement->get($idCorrespondanceLibelle) && $etablissement->get($idCorrespondanceLibelle) == $idCorrespondance) {
            return [$etablissement];
          } else {
            return [$etablissement];
          }
        }
        if (count($item) > 1) {
          $find=false;
          $result = [];
          foreach($item as $subitem) {
            $etablissement = EtablissementClient::getInstance()->find($subitem->id);
            if ($idCorrespondance && $etablissement->get($idCorrespondanceLibelle) && $etablissement->get($idCorrespondanceLibelle) == $idCorrespondance) {
              $result[] = $etablissement;
            } else {
              $result[] = $etablissement;
            }
          }
          return $resul;
        }
      }
      return [];
    }

}
