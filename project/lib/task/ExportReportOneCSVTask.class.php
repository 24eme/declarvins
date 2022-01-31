<?php

class ExportReportOneCSVTask extends sfBaseTask
{
    const DEFAULT_FROMDATE = '2000-08-01';

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
        $this->name             = 'report-one';
        $this->briefDescription = '';
        $this->detailedDescription = '';

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $interproId = 'INTERPRO-'.str_replace('INTERPRO-', '', strtoupper($arguments['interpro']));
        $current = CurrentClient::getInstance()->retrieveCurrent();
        $configuration = ConfigurationClient::getInstance()->find($current->getConfigurationId(date('Y-m-d')));
        $configurationVrac = $configuration->getConfigurationVracByInterpro($interproId);
        $interpro = InterproClient::getInstance()->find($interproId);
      	$tableCorrespondances = $interpro->correspondances->toArray();
        $correspondancesVrac = array(VracDateView::VALUE_ACHETEUR_ID, VracDateView::VALUE_VENDEUR_ID, VracDateView::VALUE_MANDATAIRE_ID);
        $correspondancesDrm = array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT);
        $date = ($options['fromdate'])? new DateTime($options['fromdate']) : new DateTime(self::DEFAULT_FROMDATE);
        $dateForView = $date->modify('-1 second')->format('c');
        $rc1 = chr(10);
        $rc2 = chr(13);
        // Vrac
        if (strtoupper($arguments['type']) == 'VRAC') {
            $items = VracDateView::getInstance()->findByInterproAndDate($interproId, $dateForView)->rows;
      		foreach ($items as $item) {
      			if ($item->value[VracDateView::VALUE_MODE_SAISIE] == 'EDI') continue;
      			$item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]));
      			$item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]));
      			$item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
      			$item->value[VracDateView::VALUE_PRIX_UNITAIRE] = round($item->value[VracDateView::VALUE_PRIX_UNITAIRE], 2);
      			$item->value[VracDateView::VALUE_PRIX_TOTAL] = round($item->value[VracDateView::VALUE_PRIX_TOTAL], 2);
        		foreach ($correspondancesVrac as $correspondance) {
        			if ($item->value[$correspondance] && in_array($item->value[$correspondance], array_keys($tableCorrespondances))) {
        				$item->value[$correspondance] = $tableCorrespondances[$item->value[$correspondance]];
        			}
        		}
                echo str_replace(array($rc1, $rc2), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)))."\n";
      		}
        }
        // DRM
        if (strtoupper($arguments['type']) == 'DRM') {
            $items = DRMDateView::getInstance()->findByInterproAndDate($interproId, $dateForView)->rows;
            $numberValues = DRMDateView::numberValues();
      		foreach ($items as $item) {
      			if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && (is_null($item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX]) || $item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX] < 0 || !$item->value[DRMDateView::VALUE_DETAIL_CVO_CODE])) {
      				continue;
      			}
      			if ($interproId != 'INTERPRO-IR' && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != 'producteur') {
      				continue;
      			}
    			foreach ($numberValues as $val) {
    				if (isset($item->value[$val])) {
    					$item->value[$val] = number_format($item->value[$val], 5, '.', '');
    				}
    			}
                echo str_replace(array($rc1, $rc2), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)))."\n";
      		}
        }
    }

}
