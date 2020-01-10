<?php

class DRMEDIImportTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('file', sfCommandArgument::REQUIRED, "Fichier csv pour l'import"),
            new sfCommandArgument('periode', sfCommandArgument::REQUIRED, "Periode de la DRM"),
            new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, "Identifiant de l'établissement (identifiant, cvi ou n° accises"),
            new sfCommandArgument('numero_archive', sfCommandArgument::OPTIONAL, "Numéro d'archive"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('date-validation', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', false),
            new sfCommandOption('facture', null, sfCommandOption::PARAMETER_REQUIRED, 'Flag automatiquement les mouvements comme facturé', false),
            new sfCommandOption('dontsave', null, sfCommandOption::PARAMETER_REQUIRED, 'Debug et pas de sauvegarde des documents', false),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'edi-import';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
    The [importVrac|INFO] task does things.
    Call it with:

      [php symfony import:drm|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $etablissement = EtablissementClient::getInstance()->find($arguments['identifiant'], acCouchdbClient::HYDRATE_JSON);

        if(!$etablissement) {
            $etablissement = EtablissementClient::getInstance()->findByNoAccise($arguments['identifiant']);
        }

        if(!$etablissement) {
            $etablissement = EtablissementClient::getInstance()->findByCvi($arguments['identifiant']);
        }

        if(!$etablissement) {
            echo "ERROR;L'établissement n'existe pas;".$arguments['identifiant']."\n";
            return;
        }

        $identifiant = $etablissement->identifiant;

        if(DRMClient::getInstance()->find('DRM-'.$identifiant.'-'.$arguments['periode'], acCouchdbClient::HYDRATE_JSON)) {
            echo "Existe : ".'DRM-'.$identifiant.'-'.$arguments['periode']."\n";
            if (!$options['dontsave']) {
                return;
            }
        }

        $drm = DRMClient::getInstance()->createDocByPeriode($identifiant, $arguments['periode']);

        if($arguments['numero_archive']) {
            $drm->numero_archive = $arguments['numero_archive'];
        }

        $configuration = ConfigurationClient::getCurrent();
        $controles = array(
                DRMCsvEdi::TYPE_CAVE => array(
                        DRMCsvEdi::CSV_CAVE_COMPLEMENT_PRODUIT => array_keys($configuration->getLabels())
                )
        );

        try {
            $drmCsvEdi = new DRMImportCsvEdiStandalone($arguments['file'], $drm, $controles);
            $drmCsvEdi->checkCSV();

            if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
                $csv = $drmCsvEdi->getCsv();
                foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
                    echo sprintf("ERROR;%s : %s;#%s\n", $erreur->diagnostic, $erreur->csv_erreur, implode(";", $csv[$erreur->num_ligne-1]));
                }
                return;
            }

            $drmCsvEdi->importCSV();

            if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
                $csv = $drmCsvEdi->getCsv();
                foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
                    echo sprintf("ERROR;%s : %s;#%s\n", $erreur->diagnostic, $erreur->csv_erreur, implode(";", $csv[$erreur->num_ligne-1]));
                }
                if ($options['dontsave']) {
                    $export = new DRMExportCsvEdi($drm);
                    echo $export->exportEDI();
                }
                return;
            }

            $drm->update();
            $validation = new DRMValidation($drm);

            if (!$validation->isValide()) {
                foreach ($validation->getErrors() as $error) {
                    echo sprintf("ERROR;%s : %s;\n", $error->getIdentifiant(), str_replace('Erreur, ', '', $error));
                }
                if ($options['dontsave']) {
                    $export = new DRMExportCsvEdi($drm);
                    echo $export->exportEDI();
                }
		        return;
            }

            if (!$options['dontsave']) {
                $drm->validate();
            }

            if($options['date-validation']) {
                $drm->valide->date_saisie = $options['date-validation'];
                $drm->valide->date_signee = $options['date-validation'];
            }

            if($options['facture']) {
                $drm->facturerMouvements();
            }

            if ($options['dontsave']) {
                $export = new DRMExportCsvEdi($drm);
                echo $export->exportEDI();
            }

            if (!$options['dontsave']) {
                $drm->save();
            }

        } catch(Exception $e) {
            echo $e->getMessage().";#".$arguments['periode'].";".$identifiant."\n";
            if(isset($options['trace'])) {
                throw $e;
            }
            return;
        }

        echo "Création : ".$drm->_id."\n";
    }

}
