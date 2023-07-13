<?php

class ExportEdiCSVTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('interpro', sfCommandArgument::REQUIRED, "Interpro"),
            new sfCommandArgument('type', sfCommandArgument::REQUIRED, "Type"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('fromdate', null, sfCommandOption::PARAMETER_OPTIONAL, 'From date', null),
        ));

        $this->namespace        = 'export';
        $this->name             = 'edi';
        $this->briefDescription = '';
        $this->detailedDescription = '';

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $interpro = 'INTERPRO-'.str_replace('INTERPRO-', '', strtoupper($arguments['interpro']));

        if ($arguments['type'] == 'vrac') {
            $configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
            if ($d = $options['fromdate']) {
                $dateForView = new DateTime($date);
                $items = VracDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows;
            } else {
                $items = VracDateView::getInstance()->findByInterpro($interpro)->rows;
            }
            foreach ($items as $item) {
      			if ($item->value[VracDateView::VALUE_MODE_SAISIE] == 'EDI') continue;
      			$item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]));
      			$item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]));
      			$item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
      			$item->value[VracDateView::VALUE_PRIX_UNITAIRE] = round($item->value[VracDateView::VALUE_PRIX_UNITAIRE], 2);
      			$item->value[VracDateView::VALUE_PRIX_TOTAL] = round($item->value[VracDateView::VALUE_PRIX_TOTAL], 2);
                echo str_replace(array(chr(10), chr(13)), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)))."\n";
      		}
        }

    }

}
