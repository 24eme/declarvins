<?php
class cravateApiSuiviReserveTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, 'Identifiant operateur'),
    	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'cravate-api';
        $this->name = 'suivi-reserve';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $result = [
            'RAISON_SOCIALE' => null,
            'CVI' => null,
            'NUMCIVP' => null,
            'EMAIL' => null,
            'TELEPHONE' => null
        ];
        $etablissement = EtablissementClient::getInstance()->find($arguments['identifiant']);
        if (!$etablissement) {
            $etablissements = EtablissementIdentifiantView::getInstance()->findByIdentifiant($arguments['identifiant'])->rows;
            if (count($etablissements) == 1) {
                $etablissement = $etablissements[0];
            }
        }
        if ($etablissement) {
            $result['RAISON_SOCIALE'] = $etablissement->raison_sociale;
            $result['CVI'] = $etablissement->cvi;
            $result['NUMCIVP'] = $etablissement->identifiant;
            $result['EMAIL'] = $etablissement->email;
            $result['TELEPHONE'] = $etablissement->email;
        }

        echo json_encode($result);
    }

}
